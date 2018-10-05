#!/usr/bin/env python

from bs4 import BeautifulSoup
from os import system

sites = []
all_categories =[]
stats =[]

for site in sites:
    stats[site] = {}
    #get feed
    #pass into bs4
    for item in site.getAll('item'):
        for category in item.getAll('category'):
            # regex it
            stats.append(category)
    calc = {}
    for item in stats:
        if calc[item] != None:
            calc[item] = 0
        calc[item] += 1
    for category in all_categories:
        output.append("<td>" + calc[category] + "</td>")
