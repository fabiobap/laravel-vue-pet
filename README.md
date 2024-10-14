## Decisões e packages

De inicial optei por usar laravel breeze(vue 3+inertia) e vuetify no front para forms/table. Layout básico pelo breeze.

Instalei o package de permissão do spatie pela rapidez/facilidade.

O projeto também está configurado com laradock.(.env.example com muitas configurações já setadas)

O projeto possui 4 models, <strong>Animal</strong>, <strong>Client</strong>, <strong>Appointment</strong> e <strong>User</strong>

Fiz somente CRUD da marcação(Appointment).

Por fins de simplicidade foi feito um seeder para criar todos usuários, roles e permissões. (além de cliente, animais e marcações)

Decidi não colocar o cliente pra ter login e ele poderá criar marcações publicamente.

## O projeto

O projeto consiste em criar marcações. 
Donos de pet podem criar marcações publicamente sem necessidade de login. Usuários logados também podem criar se tiverem permissões.

## Como é feita a marcação pública

O dono de pet preenche o formulário com os dados dele, do pet e qual a data e horário. Se preenchido corretamente será enviado um email de confirmação para o cliente.

## A lógica da marcação pública

Enviando os dados do formulário:

* será feita inicialmente uma verificação se o cliente com email passado já existe.
* será feita inicialmente uma verificação se o animal do cliente já existe.
* será criado uma marcação sem veterinário marcado.

## Parte administrativa (autenticado)

A parte administrativa só será acessada mediante login.

O usuário poderá ser recepcionista ou veterinario.

Usuários poderão ver as marcações, editar, remover ou criar dependendo das suas permissões

da recepcionista:

* Poderá criar, editar, visualizar, remover e selecionar o veterinário para aquela marcação
* Poderá ver todas marcações

do veterinário:

* Poderá visualizar e editar apenas suas marcações
* Poderá ver todas as suas marcações

## A lógica da criação da marcação administrativa

Quando a recepcionista criar/editar uma marcação e vincular à um veterinário será enviado um email com os dados da marcação para o cliente.

## Testes

O projeto tem alguns testes tantos unit quanto features, mas não cobre todos os casos por questão de tempo mesmo
