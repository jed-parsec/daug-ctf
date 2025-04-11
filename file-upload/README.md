
```markdown
```markdown
# File Upload CTF Challenge

This repository contains a Docker-based Capture The Flag (CTF) challenge focused on a file upload vulnerability. The challenge runs in a containerized web application, accessible via a browser, where players must exploit the vulnerability to retrieve a hidden flag stored at `/secret/flag.txt`. This README explains how to set up a custom Docker network (`ctf-net`) with a static IP for the container and how to build and run the challenge.

## Prerequisites

- **Docker**: Ensure Docker is installed. Verify with:
  ```bash
  docker --version
  ```
- **Git**: Required to clone this repository. Verify with:
  ```bash
  git --version
  ```
- **Repository**: Clone this repository to your local machine:
  ```bash
  git clone <your-github-repo-url>
  cd folder-upload
  ```
  Replace `<your-github-repo-url>` with the repository URL. Use a [Personal Access Token](https://github.com/settings/tokens) or SSH key for authentication.

## Directory Structure

The `folder-upload` directory contains:

```
folder-upload/
├── Dockerfile
├── app/
│   ├── index.php
│   ├── upload.php
│   └── uploads/
├── secret/
│   └── flag.txt
└── start.sh
```

- `Dockerfile`: Configures the PHP and Apache environment.
- `app/`: Holds the vulnerable web application.
- `secret/flag.txt`: Contains the flag (protected, read-only).
- `start.sh`: Starts the Apache server.

## Setup Instructions

### Step 1: Create the Docker Network (`ctf-net`)

Create a custom bridge network to assign a static IP to the CTF container.

1. **Create the Network**:
   ```bash
   docker network create --subnet=172.20.0.0/16 ctf-net
   ```
   - Subnet: `172.20.0.0/16` (IPs from `172.20.0.1` to `172.20.255.254`).
   - Name: `ctf-net`.

2. **Verify**:
   ```bash
   docker network ls
   ```
   Ensure `ctf-net` appears. Check details with:
   ```bash
   docker network inspect ctf-net
   ```

### Step 2: Build the Docker Image

Build the Docker image for the CTF challenge.

1. **Navigate to Directory**:
   ```bash
   cd folder-upload
   ```

2. **Build the Image**:
   ```bash
   docker build -t file-upload-ctf .
   ```
   - `-t file-upload-ctf`: Names the image.
   - `.`: Uses the `Dockerfile` in the current directory.

3. **Verify**:
   ```bash
   docker images
   ```
   Look for `file-upload-ctf`.

### Step 3: Run the Container with a Static IP

Run the CTF container on `ctf-net` with a static IP.

1. **Run the Container**:
   ```bash
   docker run -d \
     --name ctf \
     --network ctf-net \
     --ip 172.20.0.100 \
     -p 8080:80 \
     file-upload-ctf
   ```
   - `-d`: Runs in detached mode.
   - `--name ctf`: Names the container.
   - `--network ctf-net`: Connects to `ctf-net`.
   - `--ip 172.20.0.100`: Assigns static IP `172.20.0.100`.
   - `-p 8080:80`: Maps host port `8080` to container port `80`.

2. **Verify Container**:
   ```bash
   docker ps
   ```
   Confirm `ctf` is running with `file-upload-ctf`.

3. **Check Static IP**:
   ```bash
   docker inspect ctf | grep -i ipaddress
   ```
   Look for `"IPAddress": "172.20.0.100"`.

## Step 4: Access the Challenge

1. **Open the Web App**:
   In a browser, go to:
   ```
   http://localhost:8080
   ```
   You’ll see a file upload form.

2. **Objective**:
   Exploit the file upload vulnerability to read the flag at `/secret/flag.txt`. The flag follows the format `c4r5uc7f{...}`.

## Cleanup

After completing the challenge:

1. **Stop and Remove Container**:
   ```bash
   docker stop ctf
   docker rm ctf
   ```

2. **Remove Network** (optional):
   ```bash
   docker network rm ctf-net
   ```

3. **Remove Image** (optional):
   ```bash
   docker rmi file-upload-ctf
   ```

## Troubleshooting

- **Network Exists**:
  If `ctf-net` already exists:
  ```bash
  docker network rm ctf-net
  docker network create --subnet=172.20.0.0/16 ctf-net
  ```
- **Port Conflict**:
  If `8080` is in use, use another port (e.g., `8081`):
  ```bash
  docker run -d --name ctf --network ctf-net --ip 172.20.0.100 -p 8081:80 file-upload-ctf
  ```
  Access at `http://localhost:8081`.
- **Build Fails**:
  Ensure all files are present. Check permissions:
  ```bash
  chmod 644 app/index.php app/upload.php secret/flag.txt
  chmod 755 app/uploads start.sh
  ```
- **Git Authentication**:
  If cloning fails, generate a Personal Access Token with `repo` scope at [GitHub Settings](https://github.com/settings/tokens) and use:
  ```bash
  git clone https://<username>:<token>@github.com/<your-repo>.git
  ```

## Notes

- **Static IP**: The IP `172.20.0.100` is internal to `ctf-net`. Access the app via `localhost:8080`.
- **Security**: The flag is read-only. Do not expose the flag or solutions in this repository.
- **Challenge**: The file upload allows arbitrary files. Players must find a way to execute code and read the flag.
