from transformers import TrOCRProcessor, VisionEncoderDecoderModel
from PIL import Image
import torch
import google.generativeai as genai
import os
import sys
import json
import re
import cv2


processor = TrOCRProcessor.from_pretrained("microsoft/trocr-base-handwritten")
model = VisionEncoderDecoderModel.from_pretrained("microsoft/trocr-base-handwritten")

image_path = sys.argv[1] #"pillkill/image.png"#
image = Image.open(image_path).convert("RGB")


pixel_values = processor(images=image, return_tensors="pt").pixel_values
generated_ids = model.generate(pixel_values)
raw_text = processor.batch_decode(generated_ids, skip_special_tokens=True)[0]



genai.configure(api_key="AIzaSyDeTuXZLY7bjUjwg2Wu1LtyKL0b2tQ0008")  
model = genai.GenerativeModel("gemini-1.5-pro")


prompt = f"""
This is a handwritten medical prescription:
\"\"\"{raw_text}\"\"\"

Extract the following fields in JSON format:
- medication_name
- dosage
- timesPerDay
- takenToday (0)
- when (before meal, after meal, other)
"""

response = model.generate_content(prompt)

matches = re.findall(r'```json\s*(\{.*?\})\s*```', response.text, re.DOTALL)
if matches:
    cleaned_json = matches[0]
else:
    cleaned_json = "{}" 


parsed_output = json.loads(cleaned_json)


print(json.dumps(parsed_output))
