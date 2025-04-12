# File Rotation API - Docker Setup

This document provides instructions for running the File Rotation API using Docker.

## Prerequisites

- Docker installed on your system
- Docker Compose installed on your system

## Directory Structure

Ensure your project has the following structure before using Docker:

```
/your-project-folder
├── App/
│   ├── Controllers/
│   ├── Factories/
│   ├── Interfaces/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   ├── Strategies/
│   └── Tests/
├── data/              # Will be created automatically if it doesn't exist
├── index.php          # Entry point
├── Dockerfile
├── docker-compose.yml
├── run100.sh
└── README.md
```

## Running with Docker

1. **Build and start the container**:

   ```bash
   docker-compose up -d
   ```

   This will build the Docker image and start the container in detached mode.

2. **Access the API**:

   ```
   http://localhost:8080
   ```

   The API will be available at this URL.

3. **View logs**:

   ```bash
   docker-compose logs -f
   ```

   Use this command to see the logs from the container.

4. **Stop the container**:

   ```bash
   docker-compose down
   ```

   This will stop and remove the container.

## Configuration

The Docker setup includes:

- PHP 8.2 with Apache
- Data persistence through a volume for the `data` directory
- Automatic container restart unless explicitly stopped
- Apache configured to handle API requests properly
- Proper file permissions for web server access

## Testing in Docker

To run tests within the Docker container:

```bash
docker-compose exec app php App/Tests/FileRotationTest.php
```

## Development with Docker

For development purposes, you can modify the `docker-compose.yml` file to mount your local source code:

```yaml
volumes:
  - ./:/var/www/html
  - ./data:/var/www/html/data
```

This will make changes to your local files immediately available in the container.

# For Running <font size="5">**without docker**</font> follow this:
# 📄 File Rotation API - README

## 🛠 Description
This is a simple PHP-based backend API that stores incoming request data in text files. It rotates filenames from `100.txt` down to `1.txt`, then starts over at `100.txt`.

---

## 🚀 How to Run the Server

```bash
php -S localhost:8005
```

> Runs the API using PHP's built-in server. The default endpoint becomes:
> `http://localhost:8005/index.php`

---

## 🧪 How to Run Tests

```bash
php test.php
```

> Runs a set of automated tests that ensure files are created and rotated correctly.

---

## 📬 How to Use the API (Send 100 Requests)

```bash
bar() {
  local total=$1
  local count=0

  for i in $(seq 1 $total); do
    curl -s -o /dev/null -X POST http://localhost:8005/index.php \
      -H "Content-Type: application/json" \
      -d "{\"request\": $i}"
    ((count++))
    percent=$((count * 100 / total))
    bar=$(printf '%*s' $((percent / 2)) '' | tr ' ' '#')
    printf "\r[%s] %d%% (%d/%d)" "$bar" "$percent" "$count" "$total"
  done
  printf "\nAll done!\n"
}

bar 100
```
this is the bash named run100.sh and <font size="5">__run__</font> it via:
```bash
sh run100.sh
```
it also has some fancy bar like:

```bash 
[##################] 36% (36/100)
or:
[##################################################] 100% (100/100)
All done!
```

> This sends 100 POST requests to the API with JSON payloads, generating `100.txt` down to `1.txt` in the `data/` directory.

---

## 🧹 Clean Up

```bash
rm -rf ./data
```

> Deletes all the generated files and the `data/` directory.

---

## 📁 Project Structure

```
.
├── index.php        # The main API file
├── test.php         # Test suite for the API
└── data/            # Directory where request files are stored
```

---

## 📌 Requirements
- PHP 8.0 or higher
- `curl` (for shell testing)

---

## 📞 Contact
For questions or issues, feel free to open an issue or contact the maintainer.
