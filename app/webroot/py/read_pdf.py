#! /usr/bin/env python

# importing required classes 
from pypdf import PdfReader 
import os
import sys

#import pytesseract
#from PIL import Image

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

""" path_to_tesseract = r"C:\Program Files\Tesseract-OCR\tesseract.exe"
print(path_to_tesseract)
pytesseract.tesseract_cmd = path_to_tesseract 
image = Image.open(args[1] + args[0])
text = pytesseract.image_to_string(image)
print(text) """
