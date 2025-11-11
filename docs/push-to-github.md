# Änderungen auf GitHub pushen

Um den aktuellen Stand dieses Branches nach GitHub zu übertragen, führe die folgenden Schritte in deiner lokalen Entwicklungsumgebung aus:

1. Melde dich mit `gh auth login` oder richte deine Git-Credentials ein, falls noch nicht geschehen.
2. Überprüfe, dass alle Änderungen committet sind:
   ```bash
   git status
   ```
3. Falls noch nicht vorhanden, füge den entfernten GitHub-Remote hinzu (ersetze `<BENUTZER>` und `<REPO>`):
   ```bash
   git remote add origin git@github.com:<BENUTZER>/<REPO>.git
   ```
   oder für HTTPS:
   ```bash
   git remote add origin https://github.com/<BENUTZER>/<REPO>.git
   ```
4. Push den aktuellen Branch (`work`) nach GitHub:
   ```bash
   git push -u origin work
   ```

Solltest du in einem Fork arbeiten, achte darauf, den richtigen Remote-Namen (`origin`, `upstream`, …) zu verwenden. Wenn du zusätzliche Unterstützung brauchst, kannst du `git remote -v` ausführen, um alle konfigurierten Remotes zu sehen.
