FROM python:3.6-alpine as build

COPY requirements.txt .

RUN pip install --no-cache-dir -r requirements.txt

COPY . .

RUN python blog-report.py

FROM nginx

COPY --from=build output.html /usr/share/nginx/html/index.html
