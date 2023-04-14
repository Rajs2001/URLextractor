<!DOCTYPE html>
<html>
<head>
    <title>Link Extractor</title>
</head>
<body>
    <form method="get">
        <label for="url">Enter a URL:</label>
        <input type="text" id="url" name="url" placeholder="https://www.example.com" required>
        <button type="submit">Extract Links</button>
    </form>
    <?php
    function getLinks($url) {
        $internal_links = array();
        $external_links = array();
        $content = file_get_contents($url);
        preg_match_all('/<a\s+.*?href=[\"\']?([^\"\' >]*)[\"\']?[^>]*>(.*)<\/a>/i', $content, $matches, PREG_SET_ORDER);
        foreach($matches as $match) {
            $link = $match[1];
            if (strpos($link, 'http') !== 0) {
                $link = "$url$link";
            }
            if (strpos($link, $url) !== false) {
                $internal_links[] = $link;
            } else {
                $external_links[] = $link;
            }
        }
        return array('internal' => $internal_links, 'external' => $external_links);
    }

    if (!empty($_GET['url'])) {
        $url = $_GET['url'];
        $links = getLinks($url);
        echo "<h1>Links on $url:</h1>";
        echo "<h2>Internal Links:</h2>";
        foreach($links['internal'] as $link) {
            echo "<a href=\"$link\">$link</a><br>";
        }
        echo "<h2>External Links:</h2>";
        foreach($links['external'] as $link) {
            echo "<a href=\"$link\">$link</a><br>";
        }
    }
    ?>
</body>
</html>
