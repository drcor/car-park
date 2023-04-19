# Parte 1 do projeto Tecnologias de Internet - Grupo 002

## Informações importantes antes de avaliar o website
O ficheiro 'credentials.txt' deve ficar localizado ao mesmo nível que a pasta 'car-park'.
Exemplo:
    www/
    ├─ credentials.txt
    └─ car-park/
       └─...

Por motivos de segurança para o website, preferêncialmente apenas o conteúdo da pasta 'car-park' deve ser possível aceder através do browser.

O conteúdo do ficheiro 'credentials.txt' está divido da seguinte forma:
    - cada linha corresponde às credenciais de um e apenas um utilizador
    - cada credencial de utilizador contém o nome, hash da palavra passe e permissão do utilizador no website (user ou admin), que estão separados pelo caracter ':' (dois pontos)
Exemplo:
    <nome utilizador>:<hash da palavra passe>:<permissão do utilzador>
    professor:$2a$10$Cp1VjbscAF/K1ZCFMM3xce7HO8dxNLbSzXuk7QUH47YMiEpt6/Opm:admin


## Estrutura do projeto
.
├── car-park
│   ├── api
│   │   ├── api.php
│   │   └── files
│   │       ├── cancelaEnt
│   │       │   ├── hora.txt
│   │       │   ├── info.txt
│   │       │   ├── log.txt
│   │       │   ├── nome.txt
│   │       │   └── valor.txt
│   │       ├── cancelaSai
│   │       │   ├── hora.txt
│   │       │   ├── info.txt
│   │       │   ├── log.txt
│   │       │   ├── nome.txt
│   │       │   └── valor.txt
│   │       ├── co2
│   │       │   ├── hora.txt
│   │       │   ├── info.txt
│   │       │   ├── log.txt
│   │       │   ├── nome.txt
│   │       │   └── valor.txt
│   │       ├── humidade
│   │       │   ├── hora.txt
│   │       │   ├── info.txt
│   │       │   ├── log.txt
│   │       │   ├── nome.txt
│   │       │   └── valor.txt
│   │       ├── luzes
│   │       │   ├── hora.txt
│   │       │   ├── info.txt
│   │       │   ├── log.txt
│   │       │   ├── nome.txt
│   │       │   └── valor.txt
│   │       └── temperatura
│   │           ├── hora.txt
│   │           ├── info.txt
│   │           ├── log.txt
│   │           ├── nome.txt
│   │           └── valor.txt
│   ├── dashboard.php
│   ├── historico.php
│   ├── images
│   │   ├── cancela_off.png
│   │   ├── cancela_on.png
│   │   ├── co2.png
│   │   ├── fundo.jpg
│   │   ├── humidade.png
│   │   ├── luzes_off.png
│   │   ├── luzes_on.png
│   │   └── temperatura.png
│   ├── index.php
│   ├── logout.php
│   ├── scripts
│   │   └── dashboard.js
│   ├── styles
│   │   ├── login.css
│   │   └── style.css
│   └── utils.php
└── credentials.txt

## Autores
- Diogo Correia
- Tomás Cardoso
