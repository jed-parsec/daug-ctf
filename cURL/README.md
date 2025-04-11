# cURL CTF Challenge

This repository contains a Docker-based Capture The Flag (CTF) challenge focused on a web exploit. The goal is to retrieve a hidden flag stored at `/secret/flag.txt` by interacting with a web application running at `http://localhost:8080`. This README provides instructions for building and running the Docker image, creating a custom network (`ctf-net`), and assigning a static IP to the container.

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
  git clone https://github.com/jed-parsec/daug-ctf.git
  cd cURL
  ```
  Use a [Personal Access Token](https://github.com/settings/tokens) or SSH key for authentication, as password-based authentication is disabled.

## Directory Structure

The `cURL` directory contains:

```bash
cURL/
├── Dockerfile
├── app/
│   ├── index.php
├── secret/
│   └── flag.txt
└── start.sh
```

- `Dockerfile`: Configures the PHP and Apache environment.
- `app/`: Holds the web application with the exploit logic.
- `secret/flag.txt`: Contains the flag (protected, read-only).
- `start.sh`: Starts the Apache server.

## Setup Instructions

### Step 1: Create the Docker Network (`ctf-net`)

Create a custom bridge network to assign a static IP to the CTF container.

1. **Create the Network**:
   ```bash
   docker network create --subnet=172.20.0.0/16 ctf-net
   ```
   - **Subnet**: `172.20.0.0/16` (IPs from `172.20.0.1` to `172.20.255.254`).
   - **Name**: `ctf-net`.

2. **Verify**:
   ```bash
   docker network ls
   ```
   Ensure `ctf-net` appears. Check details with:
   ```bash
   docker network inspect ctf-net
   ```

### Step 2: Build the Docker Image

Build the Docker image for the cURL CTF challenge.

1. **Navigate to Directory**:
   ```bash
   cd cURL
   ```

2. **Build the Image**:
   ```bash
   docker build -t curl-ctf .
   ```
   - **`-t curl-ctf`**: Names the image.
   - **`.`**: Uses the `Dockerfile` in the current directory.

3. **Verify**:
   ```bash
   docker images
   ```
   Look for `curl-ctf` in the list.

### Step 3: Run the Container with a Static IP

Run the CTF container on the `ctf-net` network with a static IP.

1. **Run the Container**:
   ```bash
   docker run -d \
     --name curl-ctf \
     --network ctf-net \
     --ip 172.20.0.100 \
     -p 8080:80 \
     curl-ctf
   ```
   - **`-d`**: Runs in detached mode.
   - **`--name curl-ctf`**: Names the container.
   - **`--network ctf-net`**: Connects to the `ctf-net` network.
   - **`--ip 172.20.0.100`**: Assigns static IP `172.20.0.100`.
   - **`-p 8080:80`**: Maps host port `8080` to container port `80`.
   - **`curl-ctf`**: Uses the built image.

2. **Verify Container**:
   ```bash
   docker ps
   ```
   Confirm `curl-ctf` is running with the `curl-ctf` image.

3. **Check Static IP**:
   ```bash
   docker inspect curl-ctf | grep -i ipaddress
   ```
   Look for `"IPAddress": "172.20.0.100"`.

### Step 4: Access the Challenge

1. **Open the Web App**:
   In a browser or using a tool like `curl`, go to:
   ```
   http://localhost:8080
   ```
   You’ll see a message or the flag, depending on your approach.

2. **Objective**:
   Find a way to manipulate the HTTP request to reveal the flag at `/secret/flag.txt`. The flag follows the format `cURL{...}`.

## Cleanup

After completing the challenge:

1. **Stop and Remove Container**:
   ```bash
   docker stop curl-ctf
   docker rm curl-ctf
   ```

2. **Remove Network** (optional):
   If no longer needed:
   ```bash
   docker network rm ctf-net
   ```

3. **Remove Image** (optional):
   ```bash
   docker rmi curl-ctf
   ```

## Troubleshooting

- **Container Name Conflict**:
  If you see “container name /curl-ctf is already in use”:
  ```bash
  docker stop curl-ctf
  docker rm curl-ctf
  ```
  Then rerun the `docker run` command.

- **Network Exists**:
  If `ctf-net` already exists:
  ```bash
  docker network rm ctf-net
  docker network create --subnet=172.20.0.0/16 ctf-net
  ```

- **Port Conflict**:
  If port `8080` is in use:
  ```bash
  docker run -d --name curl-ctf --network ctf-net --ip 172.20.0.100 -p 8081:80 curl-ctf
  ```
  Access at `http://localhost:8081`.

- **Build Fails**:
  Ensure all files are present. Check permissions:
  ```bash
  chmod 644 app/index.php secret/flag.txt
  chmod 755 app secret start.sh
  ```

## Notes

- **Static IP**: The IP `172.20.0.100` is internal to `ctf-net`. Access the app via `localhost:8080` due to port mapping.
- **Security**: The flag is read-only to prevent modification. Ensure the repository doesn’t expose the flag or solutions.
- **Challenge**: The exploit involves HTTP request manipulation. Experiment with tools like `curl` to uncover the flag.
- **GitHub**: This challenge is part of `https://github.com/jed-parsec/daug-ctf.git`. For issues, open a ticket in the repository.