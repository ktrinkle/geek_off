#!/usr/bin/env python

#Geek-o-Matic ][ python control program
#Run on Raspberry Pi control unit
#Version 1.5 - add result for question from MCP

import time
import serial
import sys
import select
import pygame
import os
import psycopg2
from serial.tools import list_ports
from time import sleep
from datetime import datetime


def db_init():
#add database stuff here
    global dbhost, db_flag, yevent, team_decode
    try:
        conx = psycopg2.connect(host="localhost", dbname="@@DB_NAME@@", user="@@USER@@", password="@@PWD@@")
        cur = conx.cursor()
        cur.execute("SELECT yevent from event_name where sel_event = 1")
        yevent = cur.fetchone()[0]
        cur.close()
        db_flag = True
        #print(yevent)
        return conx
    except:
        db_flag = False
        print("DB init failed")
        return ''
#end db init stuff


def score_read(team_color):
    # Red = D, 3rd team
    # Green = E 2nd team
    # Blue = F, 1st team
    # Yellow = H, does not exist, 4th team
    global conn, team_score_list
    error = False
    db_query = ''
    print('Team color - ' + team_color)
    if team_color in team_decode:
        selected_team = team_decode[team_color]
        print("Team - " + str(selected_team))
    else:
        error = True

    db_query = "select case when ptswithbonus is null then '   0' when ptswithbonus < -999 then '-999' else lpad(cast(ptswithbonus::integer as varchar(4)),4, ' ') end from totalscore where yevent in (select yevent from event_name where sel_event = 1) and round_no = 3 and team_no = %(team_no)s;"
    if not error: #only do this if everything checks 
        with conn, conn.cursor() as cur:
            cur.execute(db_query, {'team_no':selected_team})
            score_msg = team_score_list[team_color]
            score_amt = score_msg + cur.fetchone()[0]
            print(score_amt)
            #add serial send
            
            for I in ser:
                I.write(score_amt.encode('UTF-8'))
    


def ser_init():
    #initializes serial ports. Done separately so we can fire a keypress and do this after startup

    #grab serial ports
    ser_rtn = list_ports.grep('2341:0043')

    #assign serial ports to colors so that we know what to use

    i = 0
    del(ser_device_id[ : ])
    del(ser[ : ])
    for sp in ser_rtn:
        if sp.serial_number in ard:
            ser_device_id.append((sp.device, ard[sp.serial_number]))
            #print(sp.device + ' - ' + ard[sp.serial_number] + ' - ' + sp.serial_number + '\n')
            ser.append(serial.Serial(sp.device, 115200, timeout=0, parity=serial.PARITY_NONE, stopbits=serial.STOPBITS_ONE))
            color_port.update({i: ard[sp.serial_number]})
            #print(color_port)
            i = i + 1

    print('Serial init complete')
    #end init loop


def key_init():
    #Sets up dictionary and returns value
    return_dict = {pygame.K_1:301, pygame.K_q:302, pygame.K_a:303, pygame.K_z:304, 
                   pygame.K_2:311, pygame.K_w:312, pygame.K_s:313, pygame.K_x:314,
                   pygame.K_3:321, pygame.K_e:322, pygame.K_d:323, pygame.K_c:324,
                   pygame.K_4:331, pygame.K_r:332, pygame.K_f:333, pygame.K_v:334,
                   pygame.K_5:341, pygame.K_t:342, pygame.K_g:343, pygame.K_b:344}

    return return_dict

def team_init():
    #Sets up team dictionary
    global conn, team_decode
    unit_color_list = ['B', 'G', 'R', 'Y']  #maps to scoreboard color order
    with conn:
        with conn.cursor() as cur:
            cur.execute("select tr.team_no from teamreference tr inner join roundresult rr on tr.team_no = rr.team_no and tr.yevent = rr.yevent and rr.round_no = 2 where tr.yevent in (select yevent from event_name where sel_event = 1) and rr.rnk <= 3 order by rr.rnk asc, tr.team_no asc;")
            list_team = cur.fetchmany(4)

            for j, row in enumerate(list_team):
                team_decode.update({unit_color_list[j]: row[0]})
                print('Team listing: {} {}'.format(unit_color_list[j], row[0]))


def score_update(team_color, question_id, result):
    #Updates scoring table based on team color, question, and +/- sent in result flag
    global yevent, conn, team_decode
    error = False
    db_query = ''
    point_amt = 0
    round3neg = 0
    #print('Team color - ' + team_color)
    if team_color in team_decode:
        selected_team = team_decode[team_color]
        #print("Team -" + str(selected_team))
    else:
        error = True

    if question_id < 301 or question_id > 344:
        #print('Question ID invalid')
        error = True

    if result == '-':
        round3neg = 1
        #print('Loss of points')
    elif result == '+':
        point_amt = 1
    else:
        error = True

    if not error: #only do this if everything checks 
        with conn, conn.cursor() as cur:
            db_query = "INSERT INTO scoring (yevent, team_no, round_no, question_no, point_amt, round3neg, finaljep, updatetime) values (%(yevent)s, %(team_no)s, 3, %(question_no)s, %(point_amt)s, %(round3neg)s, 0, CURRENT_TIMESTAMP) ON CONFLICT (yevent, team_no, round_no, question_no) DO UPDATE SET point_amt = %(point_amt)s, round3neg = %(round3neg)s, updatetime = CURRENT_TIMESTAMP;"
            cur.execute(db_query, {'yevent':yevent, 'team_no':selected_team, 'question_no':question_id,
                                   'point_amt':point_amt, 'round3neg':round3neg})
            conn.commit()
            #print(cur.query)


def cleanup():
    #cleans up all connections
    global db_flag, conn, ser
   
    if db_flag:
        conn.close()
        
    #if ser:
    #    ser.close()
    #ser.close fails

    #do nothing
    
def lock_loop(lock_val):

    #Sets lock status and sends to all devices
    for I in ser:
        I.write(lock_val.encode('utf-8'))

def write_curr_question(question_no):
    #Cursor defined during with statement
    global yevent, conn
    with conn:
        with conn.cursor() as cur:
            cur.execute("INSERT into curr_question (yevent, question_no, question_time) values (%(yevent)s, %(question_no)s, CURRENT_TIMESTAMP) ON CONFLICT (yevent, question_no) DO UPDATE SET question_time = CURRENT_TIMESTAMP;", 
                        {'yevent':yevent, 'question_no':question_no})
            conn.commit()
            #print(cur.query)

#Main loop


def main():

    global ser, key_decode, ser_device_id, second_count, current_question, conn

    ans_team = '' #needed outside loop since this is persistsent for answer 
    lock_state = 'L'  #starts out locked

    while 1:
        fin = ''
        keypress = ''
        ser_flag = False
        #next step - logic for breakpoint and keyboard

        keypress = ''

        events = pygame.event.get()
        for event in events:
            if event.type == pygame.QUIT:
                cleanup()
                return
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_ESCAPE:
                cleanup()
                return
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_BREAK:
                cleanup()
                return
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_SEMICOLON and lock_state == 'U':
                #unlock change
                print('Unlocked state current')
                keypress = 'L'
                lock_state = 'L'
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_SEMICOLON and lock_state == 'L':
                #lock change
                print('Locked state current')
                keypress = 'L'
                lock_state = 'U'
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_LEFTBRACKET:
                print('[ - load teams')
                if db_flag:
                    team_init()
                break
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_RIGHTBRACKET:
                print('] - serial init sequence')
                if ser:
                    ser.close()
                ser_init()  #breaks once we get to resp.decode()
                ser_flag = True
                break
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_MINUS:
                #Team doesn't get this right. Uses last team set
                #print("Team didnt get this right")
                if db_flag and current_question > 300:
                    score_update(ans_team, current_question, '-')
                    score_read(ans_team)
                    lock_state = 'L'
                    lock_loop(lock_state)
            elif event.type == pygame.KEYDOWN and event.key == pygame.K_EQUALS:
                #Team doesn't get this right. Uses last team set
                #print("Team didnt get this right")
                if db_flag and current_question > 300:
                    score_update(ans_team, current_question, '+')
                    score_read(ans_team)
                    lock_state = 'L'
                    lock_loop(lock_state)
                    current_question = 0  #removes current question
            elif event.type == pygame.KEYDOWN and event.key in key_decode:
                # Grabs key decode and sets global var. Then will eventually update DB
                current_question = key_decode[event.key]
                print('Current Question: ' + str(current_question))
                if db_flag:
                    write_curr_question(current_question)


            #print('Keypress = ' + keypress)
            
        pygame.display.flip() #kludge-o-matic, doesnt work otherwise on windows but posix is ok
        
        #return is b' + string + ', so this confounds our original plan
        #This is a python3 thing, not the case in Python 2

        if not ser_flag:
            #j = 0  may ditch this because of enumerate
            for j, I in enumerate(ser):
                resp = ''
                resp = I.read()  #only read one byte and not full string, will flush based on characters
                #print(color_port.get(j) + ' - ' + resp.decode())
                new_resp = resp.decode() #converts byte to UTF8 string
                if new_resp == 'R' or new_resp == 'G' or new_resp == 'B' or new_resp == 'Y':
                    fin = chr(ord(new_resp) + 1)
                    #print('Final - ' + fin)
                    ans_team = new_resp
                    resp = ''
                    new_resp = ''
                    break
                else:
                    #j = j + 1
                    new_resp = ''
        else:
            for I in ser:
                I.flushInput()
            fin = ''
            lock_state = 'L'
            lock_loop(lock_state)

        if keypress == 'L':
            lock_loop(lock_state)

        if fin != '':
            #k = 0
            for k, I in enumerate(ser):
                #print('Flush loop - ' + color_port.get(k))
                I.flushInput()
                if k == j:
                    I.write(fin.encode('utf-8'))
                    #print(fin)
                    I.write(b'L')
                else:
                    I.write(b'L')
                #k = k + 1
            time.sleep(second_count)
            #wait second count - variable to lock and unlock automatically
            for I in ser:
                I.flushInput()
                #I.write(b'U')
                #Automatic unlock disabled

        resp = ''
        new_resp = ''
        #k = 0

        #future - handle scoring and fire display engine after score is updated from geekomatic


if __name__ == "__main__":
    ser = []
    ser_device_id = []
    color_port = dict()
    second_count = 5
    key_decode = dict()
    current_question = 0
    yevent = ""
    team_decode = dict()
    team_score_list = {'R':'D','G':'E','B':'F','Y':'H'}

    dbhost = 'localhost'
    db_flag = False


    #define colors in dictionary
    ard = {'00000001': 'R', '00000002': 'G', '00000003': 'B', '00000004': 'Y'}

    pygame.init()
    screen = pygame.display.set_mode((100, 50))
    
    key_decode = key_init()
    conn = db_init()
    ser_init()
    time.sleep(2)
    main()

