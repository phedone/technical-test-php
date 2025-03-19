# Technical Test Diabolocom AI – Senior Software AI Backend Engineer

## Introduction
Welcome to our live coding technical test! This exercise is designed to assess how you approach problem-solving, structure your work, and think through a backend integration between Laravel (PHP) and FastAPI (Python). We are not necessarily looking for a finished or polished solution. Instead, we want to see your methodology and how you adapt when approaching a real-world integration problem.

## Overview
We have two separate repositories for this exercise:
1.	Laravel Repository
2.	FastAPI Repository

You will be integrating these two services to handle asynchronous retrieval and storage of document information.

### The Core Scenario

1.	**User & Folders**
   - A user has one or more folders.
   - Each folder may contain multiple documents.
2.	**Triggering a Document Fetch**
   - From the Laravel side, a <a href="https://www.laravelactions.com/">Laravel Action</a> will initiate a request to the FastAPI microservice, requesting document info for that user’s folders.
   - This call will be made asynchronously (the Laravel side should not block/wait in a naive manner).
3.	**Retrieving Documents (FastAPI)**
   - The FastAPI microservice will receive the request from Laravel.
   - It will then simulate fetching or scanning the folders for documents.
   - Once the document list is prepared, FastAPI will send the data back to a specific endpoint on the Laravel side.
4.	**Storing the Results (Laravel)**
   - The Laravel endpoint (separate from the initial trigger) will receive the fetched documents from the microservice.
   - Laravel will store these document details for the user (e.g., in a database).

### Authentication
- Communication between Laravel and FastAPI uses HTTP.
- You'll authenticate request via a private token with the header 'Private-Token'. You can generate it the way you want
- We can discuss some ways to increase security and why just having a private token is not enough in real cases scenario.

## What We Are Evaluating

### Thought Process & Methodology
We want to see how you approach building business logic, handling async requests, managing security, and storing data.

### Discussion & Insights
The live coding session is interactive. Ask questions, explain trade-offs, and discuss possible improvements or alternative architectures.

### Code Organization
Even though it’s a short assignment, we will look at how you structure your code and separate concerns. We'll also look at your methodology for testing

_Note: It is perfectly fine if you do not complete everything within the time limit. We care more about your problem-solving approach than a fully polished final solution._


## Setup The Laravel Project
- To setup the Laravel project, make sur that PHP >8.2 and composer is installed on your computer.

```bash
# Macos
brew install php"

# Linux
sudo apt-get install php"

# Windows Powershell
# Run as administrator...
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
```

- Then you can run:
```
composer install
```

- To run the tests:
```
composer fast-tests
```
