import pytesseract
from pdf2image import convert_from_path
import numpy as np
import cv2
import math

# OPTIONAL: Set tesseract path if needed
# pytesseract.pytesseract.tesseract_cmd = r"C:\Program Files\Tesseract-OCR\tesseract.exe"

LABELS = {
    "Name": ["first middle surname", "ሙሉ ስም"],
    "DateOfBirth": ["date of birth", "የትውልድ ቀን"],
    "Sex": ["sex", "ፆታ"],
    "Phone": ["phone number", "ስልክ"],
    "Region": ["region", "ክልል"],
    "Zone": ["zone", "ዞን"],
    "Woreda": ["woreda", "ወረዳ"]
}

def distance(a, b):
    return math.sqrt((a["x"] - b["x"])**2 + (a["y"] - b["y"])**2)

def extract_fayda_data(pdf_path):
    pages = convert_from_path(pdf_path, dpi=300)

    words = []

    for page in pages:
        img = cv2.cvtColor(np.array(page), cv2.COLOR_RGB2BGR)

        ocr = pytesseract.image_to_data(
            img,
            lang="eng+amh",
            output_type=pytesseract.Output.DICT
        )

        for i in range(len(ocr["text"])):
            if ocr["text"][i].strip():
                words.append({
                    "text": ocr["text"][i],
                    "x": ocr["left"][i],
                    "y": ocr["top"][i]
                })

    result = {}

    for field, label_variants in LABELS.items():
        label_word = None

        for w in words:
            text = w["text"].lower()
            if any(l in text for l in label_variants):
                label_word = w
                break

        if not label_word:
            result[field] = None
            continue

        # find nearest word to the right
        candidates = [
            w for w in words
            if w["x"] > label_word["x"] and abs(w["y"] - label_word["y"]) < 40
        ]

        candidates.sort(key=lambda w: distance(label_word, w))

        value = " ".join(w["text"] for w in candidates[:4])
        result[field] = value.strip()

    # SPECIAL FIELDS
    import re
    all_text = " ".join(w["text"] for w in words)

    result["FAN"] = re.search(r"\b\d{16}\b", all_text).group(0) if re.search(r"\b\d{16}\b", all_text) else None
    result["Sex"] = "Female" if "Female" in all_text or "ሴት" in all_text else "Male"
    result["Phone"] = re.search(r"\b09\d{8}\b", all_text).group(0) if re.search(r"\b09\d{8}\b", all_text) else None

    return result
