# WordPress-REST-API-to-RSS-Feed
A Simple php program to convert REST API data from Wordpress to XML Rss Feed.

## Usage

1. **Requirements**:
   - PHP installed on your server.
   - An API endpoint URL ( WordPress REST API or similar API).

2. **Setup**:
   - Clone this repository to your local machine or server.
   - Replace the placeholder values in the PHP script with your actual API URL if no `per_page`, and `page` parameters.
   - Customize cache location if necessary.

3. **Running the Script**:
   - Execute the PHP script by running:
     ```yoururl/converter.php?api=rest-api-url&page=value&per_page=value
     ```
   - The script will fetch data from the API and generate an XML feed.

4. **Caching**:
   - To improve performance, the script checks for a cached version of the feed.
   - If a cached version exists, it serves the XML feed from the cache.
   - Otherwise, it fetches fresh data from the API and creates a new cache file.
   - If the API is dynamic, use nocache version. Otherwise you will end up getting cached data after first request.

## API Parameters

- `api`: Base API URL (e.g., `https://api.example.com/posts`).
- `per_page`: Number of items per page.
- `page`: Page number.
- Read WP REST API Documentation for more info on the parameters and allowed values for each parameter.

## License

This project is licensed under the MIT License - see the LICENSE file for details.

