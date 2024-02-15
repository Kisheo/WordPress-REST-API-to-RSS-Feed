<?php
// Check if the API URL is provided
if (isset($_GET['api']) && filter_var($_GET['api'], FILTER_VALIDATE_URL)) {
    $apiUrl = $_GET['api'];
} else {
    die("Invalid or no API URL provided.");
}

// Function to fetch posts from the WordPress REST API
function fetchPosts($url) {
    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Failed to fetch data from API.");
    }
    return json_decode($response, true);
}

$posts = fetchPosts($apiUrl);

// Set headers to display XML in the browser
header('Content-Type: application/rss+xml; charset=UTF-8');
header('Content-Disposition: inline; filename=feed.xml');

echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:wfw="http://wellformedweb.org/CommentAPI/"
     xmlns:dc="http://purl.org/dc/elements/1.1/"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
     xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
    <channel>
        <title>Site Title</title>
        <link><?php echo htmlspecialchars($apiUrl, ENT_XML1, 'UTF-8'); ?></link>
        <description>Site Description</description>
        <lastBuildDate><?php echo date(DATE_RSS); ?></lastBuildDate>
        <language>en-US</language>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>

        <?php foreach ($posts as $post): ?>
            <item>
                <title><?php echo htmlspecialchars($post['title']['rendered'], ENT_XML1, 'UTF-8'); ?></title>
                <link><?php echo htmlspecialchars($post['link'], ENT_XML1, 'UTF-8'); ?></link>
                <pubDate><?php echo date(DATE_RSS, strtotime($post['date'])); ?></pubDate>
                <dc:creator><![CDATA[<?php echo htmlspecialchars($post['author'], ENT_XML1, 'UTF-8'); ?>]]></dc:creator>
                <description><![CDATA[<?php echo htmlspecialchars($post['excerpt']['rendered'], ENT_XML1, 'UTF-8'); ?>]]></description>
                <content:encoded><![CDATA[<?php echo $post['content']['rendered']; ?>]]></content:encoded>
                <guid isPermaLink="false"><?php echo htmlspecialchars($post['link'], ENT_XML1, 'UTF-8'); ?></guid>
            </item>
        <?php endforeach; ?>
    </channel>
</rss>
