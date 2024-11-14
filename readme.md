# SpeedCash ❤️ PHP
Welcome to the Official PHP API Client for the SpeedCash QRIS MPM API! 🚀

Effortlessly integrate SpeedCash’s QRIS capabilities into your PHP projects with this library. Designed to be efficient and easy-to-use, our client library allows you to quickly set up payments and access SpeedCash's powerful features.

📂 Code Samples Included: This repository comes with sample code for each essential API endpoint, so you can jump right in and start building.

💡 Explore the Docs: Find detailed documentation and examples in our API Docs for a seamless setup and to make the most of SpeedCash in your application.

Get started now and make cashless payments smoother and faster with SpeedCash!


## Table of Contents
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)

---

## Requirements

To run this project, ensure that your environment meets the following requirements:

- **PHP**: Version 8.2.x or higher. You can download PHP from [https://www.php.net/downloads](https://www.php.net/downloads).

## Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/speedcash-developer/SpeedCash-QRIS-MPM-PHP
   cd SpeedCash-QRIS-MPM-PHP
   ```
2. **Set up environment variables**:
   ```bash
    cp env-example .env
   ```

## Configuration
The project relies on environment variables for configuration. Add the following keys to your .env file:

### env

| Code                  | Description                                          | 
| --------------------- | ---------------------------------------------------- | 
| PORT                  | Port to run the server                               | 
| CLIENT_ID             | Client Identity (get after onboarding)               | 
| CLIENT_SECRET         | Client Secret (get after onboarding)                 | 
| CHANNEL_ID            | Client Channel Id                                    | 
| TOKEN_B2B             | Token After Hit Service (/access-token/b2b)          |                             
| BASE_URL              | Snap Url (get after onboarding)                      |                                    
| YOUR_URL              | lient Service Url for simulate callback transactiond |      
```

### private Key and public Key
``` bash
cb_private_key.pem =              # Private Key using RSA (2048) For Callback Geneate Signature 
cb_public_key.pem =               # Public Key using RSA (2048) For Callback Validation Signature 
private_key.pem =                 # Private Key using RSA (2048) pksc8 For Generate Service Signature 
```

## Usage
You can run specific route files directly for testing and debugging. Here’s how to manually execute a route file with php:

### Manual Route Execution
``` bash
php routes/01-credentials/access-token.php
```
``` bash
php routes/02-registrasi/merchant-status.php
```
