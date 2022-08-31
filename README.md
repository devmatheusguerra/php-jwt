# Documentação
## Pacote PHP que implementa JSON Web Token.

### Uso simples

```php
require 'vendor/autoload.php';
use Devmatheusguerra\JWT\JWT;

$jwt = new JWT();
$data = new stdClass();
$data->name = 'Devmatheusguerra';
$data->email = 'teste@gmail.com';

$token = $jwt->generate($data);
```
---

### 1.  Configurações Iniciais
Será necessária a criação de duas constantes globais. Você poderá defini-las num arquivo config.php, por exemplo.
|CONSTANTE|TIPO| Valores Aceitos |
|--|--|--|
| **SECRET_KEY_JWT** | string | Qualquer valor.
| **ALGORITHM_JWT** | string | HS256, HS384, HS512


### 2. Método *generate()*
Possui um parâmetro **não obrigatório** que corresponde ao dados personalizados que devem ser inseridos no ***payload*** do JWT. Caso queira ver mais sobre ***claim names*** [clique aqui](https://www.rfc-editor.org/rfc/rfc7519.html#section-4.1).

Por padrão já serão declarados alguns claim names:
- **iss:** Receberá o endereço do host ou localhost.
- **iat:** Utilizará o instante atual da chamada da função em *UNIX TIMESTAMP*.
- **exp:** por padrão o token irá expirar em 24 horas após a chamada.

**OBS.: Esses poderão ser sobre escritos.**
-
#### Parâmetros
| Nome | Tipo | Obrigatório |
|--|--|--|
| data | **stdClass** |  ![Não](https://icons.iconarchive.com/icons/hopstarter/button/16/Button-Delete-icon.png) |

#### Retorno
Retorna uma ***string*** correspondente ao ***token gerado***.

### 3. Método verify()
Possui um parâmetro **obrigatório** que corresponde ao token que foi recebido através da requisição do cliente.

#### Parâmetros
| Nome | Tipo | Obrigatório |
|--|--|--|
| token | **string** |  ![Sim](https://icons.iconarchive.com/icons/custom-icon-design/flatastic-9/16/Accept-icon.png)|

#### Retorno
Retorna uma ***boolean***. Verdadeiro se o token passado for válido.

### 4. Método getClaims()
Possui um parâmetro **obrigatório** que corresponde ao token que foi recebido através da requisição do cliente.

#### Parâmetros
| Nome | Tipo | Obrigatório |
|--|--|--|
| token | **string** |  ![Sim](https://icons.iconarchive.com/icons/custom-icon-design/flatastic-9/16/Accept-icon.png)|

#### Retorno
Retorna uma ***stdClass*** contendo o ***payload do token***.

### 5. Constantes
Buscando otimizar o tempo, alguns dos Status HTTP foram trazidos para a classe.
| VALOR | HTTP STATUS CODE |
|--|--|
|FORBIDDEN| 403
| UNAUTHORIZED | 401
| BAD_REQUEST | 400
| SUCCESS |200
| CREATED | 201

Exemplo de uso.
```php
$tokenValido = $jwt->verify($token_recebido);
if($tokenValido)
	http_response_code(JWT::CREATED);
else
	http_response_code(JWT::FORBIDDEN);
```