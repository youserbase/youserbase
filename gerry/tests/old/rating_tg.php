<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title>Untitled Document</title>
        <link href="yb.rating.tg.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
        <script type="text/javascript" src="yb.rating.tg.js"></script>
    </head>
    <body>
        <!--
        Erklärung
        .header sollte klar sein;
        die breite dieses ratingstars ist 85px (17px * 5) 17px star.png
        die average(von beispielsweise 37826 user) ist 80% und wird in der .ratingstars_average als width gespeichert (80% von 85px => 68px)
        in der .ratingstars_user wird der userwert gespeichert und ausgegeben. wenn die noch nicht gevotet wurde ist der wert 0 und display none (wird über die CSS-datei gesteuert);
        wenn schon gevotet wurde ist die .ratingstar auf die class .isdone zu erweitern - automatisch auf display block gesetzt;
        das peak-div speichert nichts, sondern läuft nur entweder dem cursor hinterher oder es animiert, um wie ein gummizug wieder in die aritierung zurückzuschnellen. nach jedem event/animation geht es auf width(0) zurück;
        das mask-div besteht nur aus den star.pngs
        der indicator speichert im ersten <span/> und im ersten <strong/> den prozentsatz und im zweiten die uservotes. ich blende es nur aus und überschreibe es nicht.
        das zweite <span/> ist meine canvas auf der ich texte schreibe und dynamisch verändere (was auch immer da kommen möchte)
        -->
        <div class="ratingstar">
            <div class="header">
                Beispiel
            </div>
            <div class="ratingstars">
                <div class="ratingstars_average" style="width: 68px">
                </div>
                <div class="ratingstars_user" style="width: 0px">
                </div>
                <div class="ratingstars_peak">
                </div>
                <div class="ratingstars_mask">
                </div>
            </div>
            <div class="indicator">
                <span><strong>80%</strong> von <strong>37826</strong> Bewertungen</span>
                <span style="display: none"></span>
            </div>
        </div>


        <div style="height: 100px"></div>


        <div class="ratingstar bigstar">
            <div class="header">
                Beispiel
            </div>
            <div class="ratingstars">
                <div class="ratingstars_average" style="width: 128px">
                </div>
                <div class="ratingstars_user" style="width: 0px">
                </div>
                <div class="ratingstars_peak">
                </div>
                <div class="ratingstars_mask">
                </div>
            </div>
            <div class="indicator">
                <span><strong>80%</strong> von <strong>37826</strong> Bewertungen</span>
                <span style="display: none"></span>
            </div>
        </div>


        <div style="height: 100px"></div>


        <div class="ratingstar disable">
            <div class="header">
                Beispiel
            </div>
            <div class="ratingstars">
                <div class="ratingstars_average" style="width: 68px">
                </div>
                <div class="ratingstars_user" style="width: 0px">
                </div>
                <div class="ratingstars_peak">
                </div>
                <div class="ratingstars_mask">
                </div>
            </div>
            <div class="indicator">
                <span><strong>80%</strong> von <strong>37826</strong> Bewertungen</span>
                <span style="display: none"></span>
            </div>
        </div>


        <div style="height: 100px"></div>


        <div class="ratingstar isdone">
            <div class="header">
                Beispiel
            </div>
            <div class="ratingstars">
                <div class="ratingstars_average" style="width: 68px">
                </div>
                <div class="ratingstars_user" style="width: 0px">
                </div>
                <div class="ratingstars_peak">
                </div>
                <div class="ratingstars_mask">
                </div>
            </div>
            <div class="indicator">
                <span><strong>80%</strong> von <strong>37826</strong> Bewertungen</span>
                <span style="display: none"></span>
            </div>
        </div>
	</body>
</html>
