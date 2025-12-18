#!/usr/bin/env python3
import hashlib
import socketserver
import threading
import signal
import sys

FLAG = "h3x1514{us3_str0n9_h@sh3s_&P4ssw0r9!}"

# Простые пароли для взлома
PASSWORDS = {
    "c4ca4238a0b923820dcc509a6f75849b": "1",  # MD5 от "1"
    "cd8999b61e82c7094c107358788824009c60175d": "butterfly123",  # SHA-1 от "butterfly123"  
    "916e8c4f79b25028c9e467f1eb8eee6d6bbdff965f9928310ad30a8d88697745": "qwerty098"  # SHA-256 от "qwerty098"
}

class ChallengeHandler(socketserver.BaseRequestHandler):
    def handle(self):
        self.request.sendall(b"Welcome, looking for a secret?)\n\n")
        
        # Этап 1: MD5 (хеш от "1")
        md5_hash = "c4ca4238a0b923820dcc509a6f75849b"
        self.request.sendall(f"We have identified a hash: {md5_hash}\n".encode())
        self.request.sendall(b"Enter the password for identified hash: ")
        
        answer = self.request.recv(1024).decode().strip()
        if md5_hash in PASSWORDS and answer == PASSWORDS[md5_hash]:
            self.request.sendall(b"Correct! You've cracked the MD5 hash with no secret found!\n\n")
        else:
            self.request.sendall(b"Wrong password! Connection closed.\n")
            return
        
        # Этап 2: SHA-1 (хеш от "butterfly123")
        sha1_hash = "cd8999b61e82c7094c107358788824009c60175d"
        self.request.sendall(f"Flag is yet to be revealed!! Crack this hash: {sha1_hash}\n".encode())
        self.request.sendall(b"Enter the password for the identified hash: ")
        
        answer = self.request.recv(1024).decode().strip()
        if sha1_hash in PASSWORDS and answer == PASSWORDS[sha1_hash]:
            self.request.sendall(b"Correct! You've cracked the SHA-1 hash with no secret found!\n\n")
        else:
            self.request.sendall(b"Wrong password! Connection closed.\n")
            return
        
        # Этап 3: SHA-256 (хеш от "qwerty098" с флагом)
        sha256_hash = "916e8c4f79b25028c9e467f1eb8eee6d6bbdff965f9928310ad30a8d88697745"
        self.request.sendall(f"Almost there!! Crack this hash: {sha256_hash}\n".encode())
        self.request.sendall(b"Enter the password for the identified hash: ")
        
        answer = self.request.recv(1024).decode().strip()
        if sha256_hash in PASSWORDS and answer == PASSWORDS[sha256_hash]:
            self.request.sendall(b"Correct! You've cracked the SHA-256 hash with a secret found.\n")
            self.request.sendall(f"The flag is: {FLAG}\n".encode())
        else:
            self.request.sendall(b"Wrong password! So close... Connection closed.\n")
            return

class ThreadedTCPServer(socketserver.ThreadingMixIn, socketserver.TCPServer):
    daemon_threads = True
    allow_reuse_address = True

def main():
    HOST, PORT = '0.0.0.0', 5000
    
    server = ThreadedTCPServer((HOST, PORT), ChallengeHandler)
    
    def signal_handler(sig, frame):
        print("\nShutting down server...")
        server.shutdown()
        server.server_close()
        sys.exit(0)
    
    signal.signal(signal.SIGINT, signal_handler)
    
    print(f"Server starting on {HOST}:{PORT}")
    print("h3x1514 | Хешка")
    print("Ваша цель - добыть флаг путем расшифровки хешей :)")
    
    server.serve_forever()

if __name__ == "__main__":
    main()