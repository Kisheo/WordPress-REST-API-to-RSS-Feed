<?php
// Check if the necessary parameters are provided and valid
if (isset($_GET['api'], $_GET['per_page'], $_GET['page']) &&
    filter_var($_GET['api'], FILTER_VALIDATE_URL) &&
    is_numeric($_GET['per_page']) &&
    is_numeric($_GET['page'])) {
    
    $baseApiUrl = $_GET['api']; // Base API URL
    $perPage = intval($_GET['per_page']); // 'per_page' parameter
    $page = intval($_GET['page']); // 'page' parameter

    // Construct the full API URL including 'per_page' and 'page' parameters
    $apiUrl = $baseApiUrl . '?per_page=' . $perPage . '&page=' . $page;
} else {
    die("Invalid or missing parameters. Ensure 'api', 'per_page', and 'page' are provided.");
}

// Function to fetch posts from the WordPress REST API
function fetchPosts($url) {
    $response = file_get_contents($url);
    if ($response === FALSE) {
        die("Failed to fetch data from API.");
    }
    return json_decode($response, true);
}

// Generate a unique cache file name using MD5 hash of the full API URL
$cacheFileName = md5($apiUrl) . '.xml';

// Check if a cached version of the feed exists
if (file_exists($cacheFileName)) {
    // Serve the cached XML feed if it exists
    header('Content-Type: application/rss+xml; charset=UTF-8');
    echo file_get_contents($cacheFileName);
    exit;
}

// Fetch posts if no cached version is available
$posts = fetchPosts($apiUrl);

// Start output buffering to capture the XML content
ob_start();
// Set headers to output XML content type
header('Content-Type: application/rss+xml; charset=UTF-8');

// Generate the XML content
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
<?php
// Save the generated XML content to a cache file
$xmlContent = ob_get_clean();
file_put_contents($cacheFileName, $xmlContent);

// Output the XML content
echo $xmlContent;
