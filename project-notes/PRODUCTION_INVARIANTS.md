# Sirraty Production Invariants

أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ

- App path: `/var/www/html/sirraty.com`
- Stack: Laravel
- Domain: `sirraty.com`
- Do not disturb other sites on the server.
- Apache document root must remain `/var/www/html/sirraty.com/public`.
- Public home background image is set from Cloudinary.
- Owner/Admin account: `gusgraphy@gmail.com`
- First admin setup route: `/setup/first-admin`
- Temporary setup password is stored in `.env` as `SIRRATY_SETUP_PASSWORD`.
- Database driver: MySQL, because PHP SQLite driver is not installed on this VM.
