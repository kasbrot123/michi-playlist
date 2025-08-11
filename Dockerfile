# Verwende ein offizielles Debian-Basis-Image, da es schlanker ist
# als ein vollständiges Ubuntu-Image und die benötigten Pakete enthält.
FROM debian:bullseye-slim

# Setze Umgebungsvariablen, um nicht-interaktive Installationen zu gewährleisten
ENV DEBIAN_FRONTEND=noninteractive



# Aktualisiere die Paketliste und installiere alle benötigten Pakete in einem einzigen RUN-Befehl.
# Dadurch wird die Anzahl der Ebenen im Docker-Image reduziert, was die Image-Größe verringert.
#
# - nginx: Webserver
# - python3: Python-Interpreter
# - python3-pip: Paketmanager für Python
# - python3-numpy: Python-Bibliothek für numerische Berechnungen
# - ffmpeg: Multimedia-Framework
# - libav-tools: Enthält ffprobe (manchmal in separatem Paket)
# - ca-certificates: Wichtig für sichere Verbindungen
# - curl: Ein nützliches Tool für Netzwerkoperationen
# - --no-install-recommends: Verringert die Größe des Images, indem nur
#   notwendige Abhängigkeiten installiert werden.
RUN apt-get update && \
    apt-get install -y \
    nginx \
    php-fpm \
    php-mysql \
    php-cli \
    php-gd \
    php-curl \
    php-mbstring \
    php-zip \
    python3 \
    python3-pip \
    ffmpeg \
    # libav-tools \
    ca-certificates \
    curl \
    vim
    # rm -rf /var/lib/apt/lists/*


# Den PHP-FPM Socket-Pfad in der PHP-FPM Konfiguration anpassen
# Standardmäßig lauscht PHP-FPM auf 127.0.0.1:9000, wir ändern das auf einen Unix-Socket
RUN sed -i -e "s/listen = 127.0.0.1:9000/listen = \/run\/php\/php7.4-fpm.sock/" /etc/php/*/fpm/pool.d/www.conf
RUN mkdir -p /run/php

# Setze das Arbeitsverzeichnis im Container
WORKDIR /app
COPY entrypoint.sh .
RUN chmod +x entrypoint.sh

# Kopiere die Abhängigkeitsdatei in den Container
COPY mp3_library .
RUN pip3 install -r requirements.txt


# Passe die Nginx-Konfiguration an, um auf Port 4000 zu lauschen.
# Dies ist eine einfache Konfiguration, die den Standard-Port 80 auf 4000 ändert.
COPY ./nginx.conf /etc/nginx/sites-enabled/default
# COPY ./html /var/www/html


# expose the port for nginx
EXPOSE 80


# Erstelle ein Verzeichnis für Nginx und gib dem "nginx"-Benutzer die nötigen Rechte.
# Dadurch wird verhindert, dass der Nginx-Prozess mit root-Rechten läuft, was eine
# gute Sicherheitspraxis ist.
# RUN mkdir -p /var/www/html && \
#     chown -R nginx:nginx /var/www/html

# Starte den Nginx-Webserver im Vordergrund, damit der Container aktiv bleibt.
# Die Python-Installation ist in diesem Fall nur eine Voraussetzung, die im Image
# enthalten ist, aber nicht direkt gestartet wird.
CMD ["/app/entrypoint.sh"]
# ENTRYPOINT ["/app/entrypoint.sh"]
# CMD ["nginx", "-g", "daemon off;"]
