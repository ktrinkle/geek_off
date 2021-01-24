#!/usr/bin/env python

#Geek-o-Matic ][ python scoreboard program
#Run on Raspberry Pi score display unit
#Version 1.0

import sys
import select
import pygame
import pygame.freetype
import psycopg2
import psycopg2.extensions
import math
import os
import textwrap

# Copied from https://www.pygame.org/wiki/TextWrap and modified for FreeType - KT
# draw some text into an area of a surface
# automatically wraps words
# returns any text that didn't get blitted
def drawWrapText(surface, text, color, rect, font, aa=False, bkg=None):
    #rect = pygame.Rect(rect)
    y = rect.top
    print("y: " + str(y))
    print("rect bottom: " + str(rect.bottom))
    print("rect width:" + str(rect.width))
    lineSpacing = 1.5

    # get the height of the font - fix for FreeType
    fontBox = font.get_rect("Tg") #gets bounding rect
    fontHeight = fontBox.height

    while text:
        i = 1

        # determine if the row of text will be outside our area
        if y + fontHeight > rect.bottom:
            break

        # determine maximum width of line
        print(i)
        while font.get_rect(text[:i]).width < rect.width and i < len(text):
            i += 1

        # if we've wrapped the text, then adjust the wrap to the last word      
        if i < len(text): 
            i = text.rfind(" ", 0, i) + 1

        # if we didn't wrap, truncate it
        if text.rfind(" ", 0, i) == -1:
            print("exceeded length")
            i = 1
            while font.get_rect(text[:i]).width < rect.width and i < len(text):
                i += 1
                print (i)

        # render the line and blit it to the surface
        #surface.lock()
        if bkg:
        #    font.render_to(surface, (rect.left, y), text[:i], color, bkg)
            (image, image_pos) = font.render(text[:i], color, bkg)
        else:
            (image, image_pos) = font.render(text[:i], color)

        image_pos.center = rect.center
        image_pos.y = y

        surface.blit(image, (image_pos))
        y += fontHeight + lineSpacing

        # remove the text we just blitted
        text = text[i:]

    return text


def db_init():
#add database stuff here
    global dbhost, db_flag, yevent
    try:
        conx = psycopg2.connect(host=dbhost, dbname="@@DB_NAME@@", user="@@USER@@", password="@@PWD@@")
        conx.set_isolation_level(psycopg2.extensions.ISOLATION_LEVEL_AUTOCOMMIT)
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
    global conn
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
            score_amt = cur.fetchone()[0]
            print(score_amt)


def currentRound():
    global conn
    round_no = 1
    db_query = """select max(round_no) round_no from 
                (select coalesce(rr.round_no, 1) round_no from roundresult rr where rr.yevent in 
                (select yevent from event_name where sel_event = 1) union all 
                select coalesce(ts.round_no, 1) round_no from totalscore ts where ts.yevent in 
                (select yevent from event_name where sel_event = 1)) a; """
    with conn, conn.cursor() as cur:
        cur.execute(db_query)
        round_no = cur.fetchone()[0]
        print(round_no)
    
    return round_no
    

def round1read():
    #Returns query for round 1 as text object, line by line so we can blit them.
    #Need to pad out the characters and append via spaces since this is fixed width, circa 1970s. Yay
    global conn
    round1return = []
    charspace = [2, 16, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 6, 4]
    db_query = """select ts.team_no::varchar(2) team_no, substring(tr.teamname for 16) teamname, 
                min(case when sc.question_no = 1 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q1, 
                min(case when sc.question_no = 2 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q2,
                min(case when sc.question_no = 3 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q3, 
                min(case when sc.question_no = 4 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q4,  
                min(case when sc.question_no = 5 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q5,  
                min(case when sc.question_no = 6 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q6,  
                min(case when sc.question_no = 7 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q7, 
                min(case when sc.question_no = 8 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q8, 
                min(case when sc.question_no = 9 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q9, 
                min(case when sc.question_no = 10 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q10, 
                min(case when sc.question_no = 11 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q11, 
                min(case when sc.question_no = 12 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q12, 
                min(case when sc.question_no = 13 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q13, 
                min(case when sc.question_no = 14 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q14, 
                min(case when sc.question_no = 15 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q15, 
                min(case when sc.question_no = 16 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q16, 
                min(case when sc.question_no = 17 then coalesce(sc.point_amt,0) * sp.ptsposs else null end) q17, 
                min(ts.bonus)::varchar(2) bonus, min(cast(ts.ptswithbonus as integer))::varchar(3) ptswithbonus
                from totalscore ts inner join teamreference tr
                on ts.yevent = tr.yevent and ts.team_no = tr.team_no
                left outer join scoring sc
                on ts.yevent = sc.yevent and ts.team_no = sc.team_no and ts.round_no = sc.round_no
                left outer join scoreposs sp
                on sc.yevent = sp.yevent and sc.round_no = sp.round_no and sc.question_no = sp.question_no
                where ts.yevent in (select yevent from event_name where sel_event = 1)
                group by ts.team_no, tr.teamname
                order by ts.team_no, tr.teamname;"""
    #form header
    round1return.append("                       A I R L I N E   G E E K   O F F")
    round1return.append("                       -------------   -------   -----")
    round1return.append("## TEAM NAME         1  2  3  4  5  6  7  8  9 10 11 12 13 14 15 T1 T2 BONUS TTL")
    round1return.append("-- ---------------- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- ----- ---")
    with conn, conn.cursor() as cur:
        cur.execute(db_query)
        for rec in cur:
            tempStr = ""
            for j, item in enumerate(rec):
                if item and j != 1:
                    tempStr = tempStr + str(item).upper().rjust(charspace[j], " ")
                elif item:
                    tempStr = tempStr + " " + item.upper().ljust(charspace[j], " ")
                else:
                    tempStr = tempStr + " " * charspace[j]
            round1return.append(tempStr)

    print(*round1return, sep = '\n')
    return round1return

def round23read(round, limitTeam):
    #Generic for rounds 2 and 3. Returns stock array based on last round's rank.
    global conn
    round23return = []
    db_query = """select tr.team_no, tr.teamname, ts.ptswithbonus, rr.rnk as rnk from teamreference tr 
            inner join roundresult rr on tr.team_no = rr.team_no and tr.yevent = rr.yevent 
            and rr.round_no = (%(round_no)s-1) left outer join totalscore ts on rr.team_no = ts.team_no 
            and rr.yevent = ts.yevent and rr.round_no = (ts.round_no-1) where tr.yevent in 
            (select yevent from event_name where sel_event = 1) and rr.rnk <= %(limit_amt)s 
            order by rr.rnk asc, ts.team_no asc"""
    with conn, conn.cursor() as cur:
        cur.execute(db_query, {'round_no':round, 'limit_amt':limitTeam})
        #fetch array here
        round23return = cur.fetchmany(limitTeam)

        for j, row in enumerate(round23return):
            print("Team no: {} {} Points: {} Rank: {} Row: {}",row[0], row[1], row[2], row[3], j)
        
    return round23return
        

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


def cleanup():
    #cleans up all connections
    global db_flag, conn

    if db_flag:
        conn.close()


def init_text():
#Sets up all the fonts since we only need to do this once. All the other stuff is dynamic.
    global round1Font, round2Font, round3Font, jepScoreFont
    round1Font = pygame.freetype.Font('fast-money-three.ttf', int(ROUND1_WIDTH * adj_x))
    round2Font = pygame.freetype.Font('souvenirsemibold.ttf', int(ROUND2_HEIGHT * adj_y))
    round3Font = dict({'B':pygame.freetype.Font('posterboard.ttf', int(ROUND3_WIDTH*adj_x)), 
        'G':pygame.freetype.Font('mayqueen.ttf', int(ROUND3_WIDTH*adj_x)),
        'R':pygame.freetype.Font('comicscript.ttf', int(ROUND3_WIDTH*adj_x)),
        'Y':pygame.freetype.Font('posterboard.ttf', int(ROUND3_WIDTH*adj_x))})
    jepScoreFont = pygame.freetype.Font('sportstype.ttf', int(JEPSCORE_HEIGHT * adj_y))


def round1board():
    global round1Font, screen
    #Sets up round 1 board, queries DB and blits to screen.

    background.fill(BLACK)
    screen.blit(background, (0,0))

    round1text = round1read()
    j = 0
    for i in range(0, len(round1text)):
        ht = round1Font.get_sized_height() * 1.5
        
        j = j + ht
        round1Font.render_to(screen, (0, j), round1text[i], ORANGE)
        #screen.blit(textrender, (0, j))

    pygame.display.flip()


def round2board():
    #Round 2 only
    global adj_x, adj_y
    screen_rect = screen.get_rect()
    background.fill(PYRAMIDBLUE)
    background_rect = background.get_rect()

    if screen_rect.size != SCREEN_START_SIZE:
        fit_to_rect = background_rect.fit(screen_rect)
        fit_to_rect.center = screen_rect.center
        scaled = pygame.transform.smoothscale(background, fit_to_rect.size)
        screen.blit(scaled, fit_to_rect)
        adj_x = fit_to_rect.width
        adj_y = fit_to_rect.height
    else:
        screen.blit(background, (0,0))

    #Convert logo size
    fit_rect = pygame.Rect(0, 0, int(adj_x/2), adj_y)
    if pyrlogo.get_width() > int(adj_x/ 2):
        fit_logo = pygame.transform.smoothscale(pyrlogo, (int(adj_x/2), int(adj_y/2)))
        fit_logo_rect = fit_logo.get_rect()
        fit_logo_rect.center = fit_rect.center
        screen.blit(fit_logo, fit_logo_rect)
    else:
        pyrlogo_rect = pyrlogo.get_rect()
        pyrlogo_rect.center = fit_rect.center
        screen.blit(pyrlogo, pyrlogo_rect)


    round2text = round23read(2, 6)
    j = 0
    for i in range(0, len(round2text)):
        ht = round2Font.get_sized_height() * 3

        j = j + ht
        round2Font.render_to(screen, (int(adj_x * .5), j), str(round2text[i][0]), BROWN)
        tempStr = str(round2text[i][1])
        if len(tempStr) > 16:
            tempStr = tempStr[:13] + '...'
        round2Font.render_to(screen, (int(adj_x * .6), j), tempStr, BROWN)
        if round2text[i][2]:
            round2Font.render_to(screen, ((adj_x * .9), j), str(round2text[i][2]), BROWN)


    pygame.display.flip()


def round3board():
    #Round 3 only
    global adj_x, adj_y, screen
    screen_rect = screen.get_rect()
    background.fill(BLACK)
    background_rect = background.get_rect()

    if screen_rect.size != SCREEN_START_SIZE:
        fit_to_rect = background_rect.fit(screen_rect)
        fit_to_rect.center = screen_rect.center
        scaled = pygame.transform.smoothscale(background3, fit_to_rect.size)
        screen.blit(scaled, fit_to_rect)
        adj_x = fit_to_rect.width
        adj_y = fit_to_rect.height
    else:
        screen.blit(background3, (0,0))

    #create rectangles
    outer_score_rect = pygame.Rect(0, int(adj_y * .5), int(adj_x * .3), int(adj_y * .15))
    inner_score_rect = outer_score_rect.inflate(int(adj_x * -0.015), int(adj_y * -0.015))
    outer_name_rect = pygame.Rect(0, int(adj_y * .65), int(adj_x * .3), int(adj_y * .35))
    inner_name_rect = outer_name_rect.inflate(int(adj_x * -0.015), int(adj_y * -0.015))

    score_text = [[outer_score_rect, inner_score_rect], 
        [outer_score_rect.move(int(adj_x * .333), 0), inner_score_rect.move(int(adj_x * .333), 0)],
        [outer_score_rect.move(int(adj_x * .666), 0), inner_score_rect.move(int(adj_x * .666), 0)]]

    name_text = [[outer_name_rect, inner_name_rect], 
        [outer_name_rect.move(int(adj_x * .333), 0), inner_name_rect.move(int(adj_x * .333), 0)],
        [outer_name_rect.move(int(adj_x * .666), 0), inner_name_rect.move(int(adj_x * .666), 0)]]

    #draw rects
    color_board = [BLUE, GREEN, RED, YELLOW]

    for j in range(0,3):
        pygame.draw.rect(screen, GREY1, score_text[j][0])
        pygame.draw.rect(screen, color_board[j], score_text[j][1])
        pygame.draw.rect(screen, GREY2, name_text[j][0])
        pygame.draw.rect(screen, BLUE, name_text[j][1])

    text_style = ["B","G","R","Y"]

    round3text = round23read(3, 3)
    j = 0
    for i in range(0, len(round3text)):
        ht = round3Font.get(text_style[i]).get_sized_height() * 3

        j = j + ht
        tempStr = str(round3text[i][1])

        unprinted = drawWrapText(screen, tempStr, WHITE, name_text[i][1], round3Font.get(text_style[i]))
        print(unprinted)

        #if len(tempStr) > 16:
        #    tempStr = tempStr[:13] + '...'
        #round3Font.get(text_style[i]).render_to(screen, name_text[i][1], tempStr, WHITE)
        if round3text[i][2]:
            tempStr = str(round3text[i][2])
        else:
            tempStr = "0"
        scoreBounds = jepScoreFont.get_rect(tempStr)
        scoreBounds.center = score_text[i][1].center
        scoreBounds.x = score_text[i][1].right - scoreBounds.width - int(adj_x * 0.01)
        jepScoreFont.render_to(screen, scoreBounds, tempStr, WHITE)


    pygame.display.flip()


#Main loop
def main():
    #sets up new connect that should be persistent, we hope. This is different than the other conx
    #which is used for queries.

    global conn
    try:
        cur = conn.cursor()
        cur.execute("LISTEN scoreboard;")
        print("Established connection to listener for scoreboard.")
    except:
        print("DB init failed")
        return

    while 1:
        pygame.event.pump()
        #add graceful quit from Pygame
        if pygame.event.peek(pygame.QUIT):
            return
        elif select.select([conn],[],[],30) == ([], [], []):
            print("Timeout")
        else:
            conn.poll()
            while conn.notifies:
                notify = conn.notifies.pop(0)
                round_no = notify.payload #grabs current round written to table from trigger
                print("Got Notify:", notify.pid, notify.channel, notify.payload)

                #Pull score info - convert from PHP
                #round_no = currentRound()
                if round_no == "1":
                    round1board()
                elif round_no == "2":
                    round2board()
                elif round_no == "3":
                    round3board()
                else:
                    round1board()

                pygame.display.update()


if __name__ == "__main__":
    
    #font colors
    RED         = (234,  35,   0)
    GREEN       = ( 14,  89,  10)
    BLUE        = ( 13,  21, 255)
    BLACK       = (  0,   0,   0)
    ORANGE      = (215, 255,  34)
    PYRAMIDBLUE = ( 11,  40, 130)
    BROWN       = (193, 124,  37)
    GREY1       = (153, 153, 153)
    GREY2       = (204, 204, 204)
    WHITE       = (255, 255, 255)
    YELLOW      = (  0, 255,   0)

    START_X = 1920
    START_Y = 1080 # width of the program's window, in pixels
    adj_x = START_X
    adj_y = START_Y
    SCREEN_START_SIZE = (1920,1080)
    CAPTION = "Geek Off"
    TEXT_WIDTH = 0.30
    CAP_HEIGHT = 0.0645
    TEXT_HEIGHT = 0.059
    ROUND1_WIDTH = 0.016  #sets to 80 chars wide
    ROUND2_HEIGHT = 0.015
    ROUND3_WIDTH = 0.052
    JEPSCORE_HEIGHT = 0.13

    current_question = 0
    yevent = ""
    team_decode = dict()

    #dbhost = 'localhost'
    dbhost = '10.0.1.11'
    db_flag = False
    
    global conn
    conn = db_init()

    if db_flag:

        os.environ["SDL_VIDEO_CENTERED"] = '1'
        pygame.init()
        pygame.display.set_caption(CAPTION)
        screen = pygame.display.set_mode(SCREEN_START_SIZE, pygame.RESIZABLE)
        screen_rect = screen.get_rect()
        background = pygame.Surface(SCREEN_START_SIZE).convert()
        background.fill(BLACK)
        background_rect = background.get_rect()

        if screen_rect.size != SCREEN_START_SIZE:
            fit_to_rect = background_rect.fit(screen_rect)
            fit_to_rect.center = screen_rect.center
            scaled = pygame.transform.smoothscale(background, fit_to_rect.size)
            screen.blit(scaled, fit_to_rect)
            adj_x = fit_to_rect.width
            adj_y = fit_to_rect.height
        else:
            screen.blit(background, (0,0))

        pygame.display.flip()
        pygame.display.update()


        # Define constants for images
        pyrlogo = pygame.image.load('pyramid_logo.png').convert_alpha()
        background3 = pygame.image.load('jep16star.jpg').convert()

        #initialize board
        print("Initializing text")
        init_text()

        #draw initial board
        print("Drawing initial board")
        round_no = currentRound()
        if round_no == 1:
            round1board()
        elif round_no == 2:
            round2board()
        elif round_no == 3:
            round3board()
        else:
            round1board()

        main()

