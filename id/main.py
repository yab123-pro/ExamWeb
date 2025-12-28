from fastapi import FastAPI, File, UploadFile
import requests

app = FastAPI()

OCR_API_KEY = "K85932200288957"

@app.post("/extract")
async def extract_text(file: UploadFile = File(...)):
    # Send file to OCR API
    response = requests.post(
        "https://api.ocr.space/parse/image",
        headers={
            "apikey"
        },
        files={
            "file": (file.filename, file.file, file.content_type)
        },
        data={
            "language": "amh",      # 👈 Amharic
            "isOverlayRequired": False
        }
    )

    result = response.json()

    if result.get("IsErroredOnProcessing"):
        return {"error": result.get("ErrorMessage")}

    text = result["ParsedResults"][0]["ParsedText"]
    return {
        "filename": file.filename,
        "extracted_text": text
    }
