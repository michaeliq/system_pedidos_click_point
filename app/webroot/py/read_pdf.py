#! /usr/bin/env python

# importing required classes 
from pypdf import PdfReader 
import os
import sys

# get CLI params
args = sys.argv[1:]

# creating a pdf reader object
reader = PdfReader(args[1] + args[0]) 
  
# creating a page object 
page = reader.pages[0] 
  
# extracting text from page 
text_file = page.extract_text()

# parse text to list
print(text_file)

