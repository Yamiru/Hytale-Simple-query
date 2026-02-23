# Hytale Simple Query

A lightweight UDP Query Protocol plugin for Hytale servers.  
Designed to let server-listing platforms and websites fetch real-time server data with minimal overhead.

## 🖼️ Preview Screenshot
![FAQ Screenshot](https://i.imgur.com/rRI7jom.png)



## ✨ Features

- Fully compatible UDP Game Server Query Protocol  
- Real-time player count & player list  
- TPS reporting  
- Rate-limit protection  
- IP whitelist / blacklist  
- Challenge-response authentication  
- Customizable version, map, and server name  
- Response caching for improved performance  

## 📦 Installation

1. Place `hytale-simple-query-1.0.0.jar` into the `mods/` folder  
2. Restart the server  
3. A new config will be generated at:  
   mods/Yamiru_HytaleQuery/config.json

## ⚙ Default Settings

| Setting       | Value        |
|---------------|--------------|
| Query Port    | 5533 (UDP)   |
| Bind Address  | 0.0.0.0      |
| Max Players   | 100          |
| Rate Limit    | 50 req/sec   |
| Ban Duration  | 30 seconds   |
| Challenge Auth| Enabled      |

## 🔧 Configuration

Config path: `mods/Yamiru_HytaleQuery/config.json`

```json
{
  "queryConfig": {
    "enabled": true,
    "port": 5533,
    "bindAddress": "0.0.0.0",
    "bufferSize": 4096,
    "timeoutMs": 5000,
    "cacheEnabled": true,
    "cacheDurationMs": 500
  },
  "responseConfig": {
    "serverName": "Hytale Server",
    "maxPlayers": 100,
    "gameType": "HYTALE",
    "gameId": "HYTALE",
    "map": "world",
    "showPlayers": true,
    "showPlugins": false,
    "showTps": true,
    "showVersion": true,
    "customVersion": "",
    "fakePlayers": 0,
    "fakePlayerNames": [],
    "hostPort": 5533,
    "hostIp": "0.0.0.0"
  },
  "securityConfig": {
    "rateLimitEnabled": true,
    "maxRequestsPerSecond": 50,
    "banDurationMs": 30000,
    "whitelistEnabled": false,
    "whitelistedIps": [],
    "blacklistEnabled": false,
    "blacklistedIps": [],
    "maxPacketSize": 1024,
    "challengeEnabled": true,
    "challengeTimeout": 30
  },
  "loggingConfig": {
    "logQueries": false,
    "logErrors": true,
    "logBans": true,
    "logDebug": false,
    "logStats": false,
    "statsInterval": 300
  }
}
```

## 📡 Query Protocol

The plugin follows the standard Game Server Query Protocol workflow.

### Handshake

1. Client → Server: handshake (`0x09`)
2. Server → Client: challenge token
3. Client → Server: stat request (`0x00`) + token
4. Server → Client: full server info

### Response Includes

- hostname  
- gametype  
- game_id  
- version  
- map  
- numplayers  
- maxplayers  
- hostport  
- hostip  
- tps  
- plugins  
- player list  

## 🔥 Firewall

UFW:
```bash
sudo ufw allow 5533/udp
```

iptables:
```bash
sudo iptables -A INPUT -p udp --dport 5533 -j ACCEPT
```

## 📘 Requirements

- Java 25+
- Hytale Server

## 📄 License

MIT License


## 📧 Contact

- **Website**: [yamiru.com](https://yamiru.com)
- **GitHub**: [@Yamiru](https://github.com/Yamiru)
- **Issues**: [GitHub Issues](https://github.com/Yamiru/Hytale-Simple-query/issues)
