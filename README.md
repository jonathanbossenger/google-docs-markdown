# Google Docs Markdown

Push/pull markdown to/from Google Docs and make suggestions

1. Pull: Get Doc contents and save as local markdown file
2. Push: Take local Markdown file and push to Google Doc
3. Suggest: Make (suggestions)[https://developers.google.com/docs/api/how-tos/suggestions], which are effectively deferred edits waiting for approval.

## Enable Google API

As at 2021/03/10

- Go to https://developers.google.com/docs/api/quickstart/php
- Change the project name if you prefer (eg Docs Markdown)
- Click the Enable the Google Docs API button
- Give the project a name, click Next
- Configure OAuth Client for Desktop app, click Create
- Click Download Client Configuration and store credentials.json

## Start up

The first time you run the sample, it will prompt you to authorize access:

- Browse to the provided URL in your web browser.
- If you are not already logged into your Google account, you will be prompted to log in. If you are logged into multiple Google accounts, you will be asked to select one account to use for the authorization.
- Click the Accept button.
- Copy the code you're given, paste it into the command-line prompt, and press Enter.
