FROM python:3.9-slim

WORKDIR /app

RUN apt-get update && apt-get install -y \
    netcat-traditional \
    && rm -rf /var/lib/apt/lists/*

COPY challenge.py .
COPY passwords.txt .

EXPOSE 5000

CMD ["python", "challenge.py"]