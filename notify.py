import psycopg2
import time
import smtplib
import os
from email.mime.text import MIMEText
from email.mime.multipart import MIMEMultipart

# --------- CONFIG ENV ----------
DATABASE_URL = os.environ["DATABASE_URL"]

EMAIL_FROM = os.environ["EMAIL_FROM"]
EMAIL_PASSWORD = os.environ["EMAIL_PASSWORD"]
SMTP_SERVER = os.getenv("SMTP_SERVER", "smtp.gmail.com")
SMTP_PORT = int(os.getenv("SMTP_PORT", 587))
# ------------------------------

def load_template(task_title):
    with open("email_template.html", "r", encoding="utf-8") as f:
        html = f.read()
    return html.replace("{{TASK_TITLE}}", task_title)

def send_email(to_email, task_title):
    msg = MIMEMultipart("alternative")
    msg["From"] = EMAIL_FROM
    msg["To"] = to_email
    msg["Subject"] = "⏰ Rappel de tâche"

    html_content = load_template(task_title)
    msg.attach(MIMEText(html_content, "html"))

    with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as server:
        server.starttls()
        server.login(EMAIL_FROM, EMAIL_PASSWORD)
        server.send_message(msg)

def main():
    conn = psycopg2.connect(DATABASE_URL)

    while True:
        cur = conn.cursor()
        cur.execute("""
            SELECT t.id, t.title, u.email
            FROM public.tasks t
            JOIN public.users u ON t.user_id = u.id
            WHERE t.status = 'pending'
            AND t.notified = FALSE
            AND t.date BETWEEN NOW() + INTERVAL '5 minutes'
                           AND NOW() + INTERVAL '6 minutes'
        """)

        tasks = cur.fetchall()

        for task_id, title, email in tasks:
            send_email(email, title)

            cur.execute(
                "UPDATE tasks SET notified = TRUE WHERE id = %s",
                (task_id,)
            )
            conn.commit()

            print(f"📧 Email envoyé à {email} → {title}")

        cur.close()
        time.sleep(60)

if __name__ == "__main__":
    main()
