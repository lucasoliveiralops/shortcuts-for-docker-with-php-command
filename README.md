# Encurtador de comandos Docker

Esse projeto tem a única finalidade de incurtar comandos Docker.

Sua principal finalidade é para quando se é necessário acessar os containeres via CMD diversas vezes ao dia, assim economizando bastante tempo.


**Exemplos de comandos dentro do container**

```bash
 de ls 
```

**de** é o comando implementado por este projeto.

No exemplo acima ele irá reproduzir: 'docker exec -it nome_container sh' + comando 

**Exemplos de restart de container**

```bash
 de restart
```

**de restart** reinicia o  container desejado.
## Dependências / Compatibilidade

**Esse projeto foi feito unicamente pensando ambientes Linux, porém pode ser facilmente adaptado para Windows / Mac.**



| Dependências   | Versão       
| :---------- | :--------- |
| `PHP` | `*` |
| `Docker` | `*` |
| `Docker Compose` | `Opcional` |


## Instação

```bash
  git@github.com:lucasoliveiralops/shortcuts-for-docker-with-php-command.git
  sudo mv shortcuts-for-docker-with-php-command/de.php  /usr/local/bin/de
  sudo chmod +x /usr/local/bin/de 
```

## Configuração

Basicamente existe dois arquivos: ".settings-from-op-shell" e "'.suggar-syntax-from-op-shell".

**.settings-from-op-shell**: Ele é o arquivo responsável por configurar o caminho / nome do container.


**.suggar-syntax-from-op-shell**: Ele é o arquivo responsável por criar suggar synxtax, exemplo, quero rodar um teste com php unit, porém não quero escrever um comando tão grande, então usarei esse arquivo para criar uma suggar synxtax e reduzir o comando.

**Caminho dos arquivos de configuração: /home/root**

**Os exemplos dos arquivos se encontram no Projeto.**