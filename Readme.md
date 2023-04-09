https://qpalma.myqnapcloud.com:8081
https://qpalma.myqnapcloud.com:8081/phpMyAdmin/index.php?db=cms&table=users&target=sql.php

ABOUT>
Frontend:(subfolder templates/include with reocurring parts like header.php etc.)

    http://localhost/cms/index.php => Main page - main recipe categories -> leads to Category page
    Template > homepage.php

    http://localhost/cms/?action=archive => Show all recipes - not sure what to do with this
    Template > archive.php

    http://localhost/cms/?action=viewCategory => Category page - listing articles by category
    Template > viewArticles.php

    http://localhost/cms/index.php?action=searchForArticle&q= => Search page - listing of search by name/kategory - accesible from every page
    Template > viewArticles.php

    http://localhost/cms/?action=policy => Privacy Policy page
    Template > viewPrivacyPolicy.php

    http://localhost/cms/?action=contactForm => Contact form page
    Template > contactForm.php

    http://localhost/cms/?action=aboutUs =>About us page
    Template > aboutUs.php

    http://localhost/cms/?action=viewArticle&articleId=%% => show only 1 article
    Template > viewArticle.php


ADMIN STUFF - USER LOGGED IN OR WANTS TO REGISTER
    https://qpalma.myqnapcloud.com:8081/cms/admin.php?action=register => Shows registration form
    Template > admin\registrationForm.php

    https://qpalma.myqnapcloud.com:8081/cms/admin.php => Login, or when logged in, shows homepage, but with user functions
    Template > admin.php
    Template > loginForm.php

    http://localhost/cms/admin/editArticle.php => New, editing already existing article, accesible only to users
    Template > admin/editArticle.php

            UserDetails page - changing name, password, admin functions for admin, statistics

            My Account page - user settings, foto, info, password change, delete account

            Favourite recipes page - shows only if user have some

Backend :
    http://localhost/cms/index.php => prepares data for the template (homepage.php)

    http://localhost/cms/?action=archive => prepares data for the template (archive.php)
    CONSULT:dont know why there are many calls to DB or how it functions

    http://localhost/cms/?action=viewCategory => prepares data for the template (viewArticles.php)

    http://localhost/cms/index.php?action=searchForArticle&q=xyz => prepares data for the search results (viewArticles.php)

    http://localhost/cms/?action=policy => prepares data for the privacy policy page (viewPrivacyPolicy.php)

    http://localhost/cms/?action=contactForm => prepares data for the contact form page (contactForm.php)

    http://localhost/cms/?action=aboutUs => prepares data for the contact form page (aboutUs.php)


    ADMIN FUNCTIONS
    http://localhost/cms/admin.php?action=register => prepares data for the registration form page (admin\registrationForm.php), atempts to create user(access to DB) and handles errors

    http://localhost/cms/admin.php => if the user is not logged in, it will show login template (loginForm.php)

    http://localhost/cms/admin.php?action=login => code will handle username and password given and resolves if its
    -correct(admin.php) => with SESSION created
    -incorrect(loginForm.php)
    Logged in == added functionality to the user - add recipe from main page, delete own recipes etc.

    http://localhost/cms/admin.php?action=logout => unsets session and shows homepage (index.php)

    http://localhost/cms/admin.php?action=editArticle&articleId=%% => New article and edit article handling (/admin/editArticle.php)

    https://localhost/cms/admin.php?action=deleteArticle&articleId=% => Deletes article by article id

USED LIBRARIES AND FRAMEWORKS>
    Nepouzivame Bootstrap..!?





DONE
Zabranit zmenseniu pod 386px(teraz je na 311px) na sirku viewport?
zmenit dlzku input pre vyhladavanie o 1 znak viac
Quotes - pridat novu db table, napisat novu class aj funkcie - zobrazovanie random
edit article(upravit recept)-vsetky informacie by sa mali ukazat uzivatelovi aby ich vedel prepisat
new article(pridat recept)-prazdne kolonky funguje
Pridany certbot(spanielsky rozprava iba)
Pridat recept na stranke Articles(alebo vsade) ak je niekto prihlaseny
nezobrazuju sa nazvy articlov dobre (regex upraveny)
vyriesenie zahady s vkladanim sk textu do db
Vytvorit CLASS USER, Log in s databazou spojit(metody checkUser-> ci uz neexistuje v db niekto s takym menom
getUserById,deleteUser, changePassword, changeUsername,
Tooltips
pageTitle sa nemeni, doplnit do head
Pocet vysledkov vo vyhladavani
User info - kolko receptov pridal(moje recepty),registrovany od (datum), etc
vymazanie article z Article page (majitel receptu & prihlaseny | admin)
Edit categories - only by admin
znazornit spravny total receptov v headeri
Previous-Next Article sipky/funkcie nefunguju spravne
-zmena mena-neda sa zmenit na to iste meno ked su ine velke/male pismena==> vyhodnotenie case sensitive uz pri vzniku alebo iba pri zmene mena?
Zobrazit oblubene articles/recepty(user related),favourite recept(oznacenie userom a zobrazenie iba userovi)
-User: foto(profilovka)
-User: popis osoby
moznost ukazat nahlad - zobraziť heslo pri vyplnani
pridat email do class USER, do DB, a do formularu registracia aj do Userdetails
zacat robit reset/zabudnutie hesla-> odoslanie do emailu
Urobit GITHUB repositar a vsetko zalohovat
9.6.2021 - zmena loga na sk (recipees_logo_sk.svg)
9.6.2021 - zmena farieb (colors.css)
9.6.2021 - header changes
10.6.2021 - loginform changes
11.6.2021 - registration form changes
11.6.2021 - hiding footer when in mobile screens javascript code (hideFooter.js)
13.6.2021 - contact form changes
13.6.2021 - changing logo by scrolling down added (shrinkHeader.js)
14.6.2021 - update to shrinkHeader.js



TODO>
Nefunguje contact form? (zrevidovat)

Admin.php DeleteArticle() unauthorized doriesit.

Popisat ktore casti aplikacie su Model,View,Controller, Diagram stranky

CSS hlavnej stranky, vsetky karty s receptami musia vypadat tak isto = ziadne rozdielne velkosti fotiek atd. Karty mensie!

Search rozbalovaci z menu? klikatelna ikonka az potom sa rozbali aby nezavadzal?

resetPassForm.php --> neviem k comu sluzi!!
dorobit shrinkHeader.js --> prerobit obrazok a CSS k tomu aby sa logo posunulo dolava a menu napravo

robots.txt --> BLOCK ACCESS TO THE ADMIN PART

404 stranka / not found custom made

znazornit foto uz pri vybere-vzniku noveho receptu, pridat viacere fotky do swiperu?
CSS 320x480

contact form upravit - funkciu do index/admin? + CSS


-reset hesla => poslat docasne na mail?(doplnit mail do db pri registracii?)
-User: email(reset hesla)
-User pri zmene hesla -> zadaj stare heslo + zadaj nove heslo 2x?

ADMIN FUNCTIONS IN SECTION USERDETAILS?
-Vymazanie vsetkych articles by user
-vymazanie article by user
-statistiky(pre admina?)
-set admin by userid
-unset admin by userid
-zoznam-tabulka vsetkych userov so statistikami/prepinanim deleteUser/blockUser/makeAdmin/zadanie noveho hesla userovi? etc?

-Premenovanie username na (meno + "pouzive1 zrusil konto") pri odregistrovani usera
-User pri registracii check ci je role value==1, aby nebolo mozne odoslat formular s 0(admin)
-momentalne hocikto moze menit kategorie..opravit

Calls to Action (CTA)

pridat novu kategoriu na homepage zvlast button

Podkategorie -- cast db, vsetko=Moznost pridat novu Kategoriu aj POD-kategoriu

Logo slovensky/anglicky

Dark/Light mode of the page?

chatbot z-index.. nedari sa

komenty pod receptom? (datum,cas,username)

share link na recept/qr code?

hodnotenie receptu?
adoptovat recept?(ked autor receptu zmazal konto bude moct nejaky iny uzivatel si ho prisvojit?)

about us + sitemap + inverse logo + business contact info/phone/email + copyright +  subscribe new recipes
About us PAGE + location -maps ?

potvrdenie cookies/privacyPolicy -> zatial pozadie blurred

https://www.w3schools.com/php/php_file_upload.asp --> create file upload
doplnit do SQL db majitel-user kto vytvoril recept

Advanced search?

oznam o uspesnej zmene ked vznikne novy recept alebo aj zanikne alebo sa upravi
$results['statusMessage'] --> dokoncit vsetky upozornenia na stranke

streamlining-> ist kod po kode admin.php, index.php, vymazat/prerobit duplikatny kod

Pagination - on article page, mozno aj na category page

Articles Sort by what?

########## FRONTEND ##########

- prihlasovaci formular - urobit klikatelny aj text pri policku "zviditelnit znaky"
- validuje sa form aj po kliknuti na zabudol som heslo (vypise please fill out this field tam kde nema)
- zaslanie zabudnuteho hesla do emailovej stranky je na mobile skarede
- chyba favicon
- tlacitko pridat recept sa divne meni ked sa rozbaluje
- formular na pridanie recepta je dost blaznivy
- tooltip text sa zobrazuje



########## BACKEND ##########
DONE - nemalo by sa mi pri receptoch ktore nie su moje zobrazovat menu ze modify a delete
- pri prezerani kategorii by sipky mali zoradit clanky podla nejakeo kluca
- ked je iba jeden clanok v kategorii, sipky dolava a doprava by sa nemali zobrazovat
- do hlavneho menu vlavo hore by sme mali pridat "zobrazit kategorie clankov"
- po kliknuti na autora chceme vsetky jeho clanky
