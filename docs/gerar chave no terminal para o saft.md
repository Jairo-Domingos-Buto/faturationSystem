# 1. Criar a pasta segura dentro de storage
mkdir -p storage/app/agt/keys

# 2. Gerar a Chave Privada (1024 bits - Padrão AGT)
openssl genrsa -out storage/app/agt/keys/private_key.pem 1024

# 3. Gerar a Chave Pública (Para extraires e enviares na declaração Modelo 8 da AGT mais tarde)
openssl rsa -in storage/app/agt/keys/private_key.pem -pubout -out storage/app/agt/keys/public_key.pem
