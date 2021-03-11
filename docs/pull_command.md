# Pull command

`php pull_md.php <document_id>`

or 

`php pull_md.php <document_id> <path_to_create_output>`

## Description

Pull a Google Document which contains markdown to a local markdown file.

- document_id (required): the 44 character unique id for the Google Document
- path_to_create_output: local directory path in which to create the markdown file. Requires the trailing slash. If not provided, will create the file in the current working directory.

## How to find the document_id

Given the Google Docs URL

`https://docs.google.com/document/d/195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE/edit`

The document_id is

`195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE`

## Examples

`php pull_md.php 195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE`

`php pull_md.php 195j9eDD3ccgjQRttHhJPymLJUCOUjs-jmwTrekvdjFE /home/docs/project-name/`

