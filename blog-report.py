#!/usr/bin/env python

# edci336-blog-categories
# https://github.com/richmccue/edci336-blog-categories
# By Ryan McCue https://ryanmccue.ca
# By Rich richmccue https://richmccue.com
# GPL 3 License

from bs4 import BeautifulSoup
import requests
from os import system
import re

def main():
    # Add table for generated output
    output = '<table class="table"><tr>'

    # Get list of sites from text file
    with open('blogs_feeds.txt', 'r') as categoriesFile:
        sites = list(filter(None, categoriesFile.read().split('\n')))

    # Get list of categories from text file
    with open('categories.txt', 'r') as categoriesFile:
        all_categories = list(filter(None, categoriesFile.read().split('\n')))

    # Generate header for table
    output += '<th>Site</th>'
    for category in all_categories:
        output += '<th>' + category + '</th>'
    output += '</tr>'

    # Loop through the sites
    for site in sites:
        # Add table row
        output += '<tr>'

        # Get source code for site
        source_code = requests.get(site)
        plain_text = source_code.text

        # Add it to beautiful soup
        soup = BeautifulSoup(plain_text, 'html5lib')

        # Keep track of categories each blog post has
        site_categories = []

        # Loop through each blog post
        for item in soup.findAll('item'):

            #find the blog post URL
            blog_post_url = str(re.match(r'<link>(.*)<\/link>', str(item)))

            # Loop through each category in the blog post
            for category in item.findAll('category'):
                # Use regex to parse category from XML
                match = re.match(r'<category><\!--\[CDATA\[(.*)\]\]--></category>', str(category))

                # If the regex does not match, continue
                if match is None:
                    continue

                # Append the category to the site categories list
                site_categories.append(match.group(1))

        # Create a dictonary for counting number of posts per category
        calc = {}
        blog_post_urls = {}

        # Loop through list of categories
        for item in site_categories:

            # If the category is not in the dict, set value to zero
            if calc.get(item.lower()) is None:
                calc[item.lower()] = 0

            # Increment the category by one
            calc[item.lower()] += 1

        # Add website to table row data
        output += '<td>' + site + '</td>'

        # Loop through relevant categories to get wanted categories for table
        for category in all_categories:
            # Add each category, or if does not exist zero, to table
            output += '<td><a href=\"' + blog_post_url + '\">' + str(calc.get(category.lower(), 0)) + '</a></td>'

        # End table row
        output += '</tr>'

    # End table and add bootstrap styles to make it look nicer
    output += '</table><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">'

    # Open output.html file and write the table to it
    f = open('output.html', 'w')
    f.write(output)


if __name__ == "__main__":
    main()
