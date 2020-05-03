<?php
//Criar as constantes com as credencias de acesso ao banco de dados.
define('HOST', '');
define('USER', '');
define('PASS', '');
define('DBNAME', '');
define('PORT', '');


class Conexao {
    //Cria a conexão com banco de dados usando o PDO e a porta do banco de dados

    public static $instance;

    private function __construct() {
        //construtor privado e vazio pra evitar a criação de novas instancias do banco

    }

    public static function getInstance() {
    //Conexao com o banco de dados no padrao singleton, 
    //retorna uma instancia da classe

        if (!isset(self::$instance)) {
            self::$instance = new pdo('pgsql:host=' . HOST . ';port=' . PORT . ';dbname=' . DBNAME, USER, PASS);    
        }
        return self::$instance;
    }
}

class Utils {
    public static function criptografar($string){
    // Função para criptografar strings utilizando o algoritmo blowfish (bcrypt)
        $options = [
            'cost' => 12,
        ];
        $string = password_hash($string, PASSWORD_BCRYPT, $options);
        return $string;
    }
}

class Categoria {
    private $id;
    private $descricao;

    function __construct($id = 0, $descricao = ""){
        $this -> id = $id;
        $this -> descricao = $descricao;
    }

    function setId($id){
        $this -> id = $id;
    }


    function getId(){
        return $this -> id;
    }

    function setDescricao($descricao){
        $this -> descricao = $descricao;
    }

    function getDescricao(){
        return $this -> descricao;
    }


    public function save() {
    // logica para salvar categoria no banco   

        $sql = "INSERT INTO categoria(descricao) values ('{$this -> descricao}')";        
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }

    public function update() {
    // logica para atualizar categoria no banco

        $sql = "UPDATE categoria SET descricao='{$this -> descricao}' WHERE id = {$this -> descricao}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }

    public function remove($id) {
    // logica para remover categoria do banco

        $sql = "delete from categoria where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }

    public function listById($id) {
    // logica para listar categoria pelo nome

        $sql = "select * from categoria where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {
            return new Categoria($row['id'], $row['descricao']);
        }

    }
    
    public function listAll() {
    // logica para listar todas as categorias do banco

        $categorias = array();
        $sql = "select * from categoria order by id";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();

        while ($row = $stmt -> fetch()) {
            array_push($categorias, new Categoria($row['id'], $row['descricao']));
        }
        return $categorias;
    }
}


class Status {
    private $id;
    private $descricao;


    function __construct($id = 0, $descricao = ""){
        $this -> id = $id;
        $this -> descricao = $descricao;
    }

    function setId($id){
        $this -> id = $id;
    }

    function setDescricao($descricao){
        $this -> descricao = $descricao;
    }

    function getId(){
        return $this -> id;
    }

    function getDescricao(){
        return $this -> descricao;
    }

    public function save($status) {
    // logica para salvar status no banco

        $sql = "INSERT INTO duvida_status(descricao) values ('{$this->status->getDescricao()}')";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function update() {
    // logica para atualizar status no banco

        $sql = "UPDATE duvida_status SET descricao='{$this -> descricao}' WHERE id = {$this -> id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function remove($id) {
    // logica para remover status do banco
    
        $sql = "delete from duvida_status where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function listById($id) {
    // logica para listar status pelo nome
        $sql = "select * from duvida_status where id={$id}";        
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
         while ($row = $stmt -> fetch()) {
             return new Status($row['id'], $row['descricao']);
            }
    }

    public function listAll() {
    // logica para listar todos os status do banco
        $estados = array();        
        $sql = "select * from duvida_status order by id";           
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {
            array_push($estados, new Status($row['id'], $row['descricao']));
        }
        return $estados;
    } 
}

class Usuario {
    private $id;
    private $email;
    private $nome;
    private $senha;
    private $pontos_bonificacao;

    function __construct($id = 0, $nome = "", $email = "", $senha = "", $pontos_bonificacao = ""){
        $this -> id = $id;
        $this -> nome = $nome;
        $this -> email = $email;
        $this -> senha = $senha;
        $this -> pontos_bonificacao = $pontos_bonificacao;
    }

    function setId($id){
        $this -> id = $id;
    }

    function setEmail($email){        
        $this -> email = $email;

    }

    function setNome($setNome){
        $this -> nome = $setNome;
    }

    function setSenha($senha){
        $this -> senha = Utils::criptografar($senha);
    }

    function setPontosBonificacao($pontos_bonificacao){
        $this -> pontos_bonificacao = $pontos_bonificacao;
    }

    function getId(){
        return $this->id;
    }

    function getEmail(){
        return $this->email;
    }
    function getNome(){
        return $this->nome;
    }

    function getSenha(){
        return $this->senha;
    }

    function getPontosBonificacao(){
        return $this->pontos_bonificacao;
    }

    public function save() {
    // logica para salvar usuario no banco

        $sql = "INSERT INTO usuario(email, nome, senha, pontos_bonificacao)
            values ('{$this-> email}', 
                    '{$this-> nome}', 
                    '{$this -> senha}', 
                    {Utils::criptografar($this -> pontos_bonificacao)})";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function update() {
    // logica para atualizar usuario no banco

        $sql = "UPDATE usuario SET email='{$this-> email}', nome = {$this -> nome}, pontos_bonificacao={$this ->pontos_bonificacao }  WHERE id = {$this -> id}";                
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function remove($id) {
    // logica para remover usuario do banco
        $sql = "delete from usuario where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
        
    public function listById($id) {
    // logica para listar usuario pelo id

        $sql = "select * from usuario where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {            
            return new Usuario(
                            $row['id'], 
                            $row['nome'], 
                            $row['email'], 
                            $row['senha'], 
                            $row['pontos_bonificacao']
                        );
        }

    }
    public function listAll() {
    // logica para listar todos os usuarios do banco
        $usuarios = Array();
        $sql = "select * from usuario order by id";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {
            array_push($usuarios, new Usuario(
                                            $row['id'], 
                                            $row['nome'], 
                                            $row['email'], 
                                            $row['senha'], 
                                            $row['pontos_bonificacao']
                                        ));            
        }
        return $usuarios;
    }

    public function list($id = -1){
        if($id == -1){
           return $this -> listAll();
        }else{
            
            return array($this -> listById($id));
        }
    }
}

class Duvida {
    private $id;
    private $titulo;
    private $descricao;
    private $categoria_id;
    private $usuario_id;
    private $status_id;

    function __construct($id = 0, $titulo = "", $descricao = "", $categoria_id =    0, $usuario_id = 0, $status_id=0){
        $this -> id = $id;
        $this -> titulo = $titulo;
        $this -> descricao = $descricao;
        $this -> categoria_id = $categoria_id;
        $this -> usuario_id = $usuario_id;
        $this -> status_id = $status_id;
    }

    function setId($id){
        $this->id = $id;
    }

    function setTitulo($titulo){
        $this->titulo = $titulo;
    }

    function setDescricao($descricao){
        $this->descricao = $descricao;
    }
    function setCategoriaId($categoria_id){
        $this->categoria_id = $categoria_id;
    }
    function setUsuarioId($usuario_id){
        $this->usuario_id = $usuario_id;
    }
    function setStatusId($status_id){
        $this -> status_id = $status_id;
    }

    function getId(){
        return $this->id;
    }

    function getTitulo(){
        return $this->titulo;
    }

    function getDescricao(){
        return $this->descricao;
    }
    function getCategoriaId(){
        return $this->categoria_id;
    }
    function getUsuarioId(){
        return $this->usuario_id;
    }
    function getStatusId(){
        return $this ->status_id ;
    }

    public function save() {
        // logica para salvar duvidas no banco

        $sql = "insert into  duvida (titulo, descricao, categoria_id,usuario_id, status_id)
        values('{$this ->titulo}', '{$this->descricao}', {$this->categoria_id}, {$this->usuario_id}, {$this ->status_id})";         
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
            
    public function update() {
        // logica para atualizar duvidas no banco

        $sql = "UPDATE duvida SET titulo ='{$this->titulo}', descricao = '{$this->descricao}', categoria_id={$this->categoria_id}, usuario_id={$this->usuario_id}, status_id={$this->status_id} WHERE id = {$this->id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
            
    public function remove($id) {
        // logica para remover duvidas do banco

        $sql = "delete from duvida where id={$id}";
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
    }
    
            
    public function listById($id) {
        // logica para listar duvidas pelo id
        $sql = 
        "select 
            duvida.id as id, 
            duvida.titulo,
            duvida.descricao, 
            categoria.id as categoria, 
            usuario.id as usuario, 
            duvida_status.id as status
        from
            duvida 
            inner join categoria 
                on duvida.categoria_id = categoria.id
            inner join usuario
                on duvida.usuario_id = usuario.id
            inner join duvida_status
                on duvida.status_id = duvida_status.id where duvida.id={$id}";                        
        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {
            return new Duvida($row['id'], $row['titulo'], $row['descricao'],$row['categoria'], $row['usuario'], $row['status']);
        }

    }
    public function listAll() {
        // logica para listar todos os duvidas do banco
        $duvidas = array();        
        $sql = 
        "select 
            duvida.id as id, 
            duvida.titulo, 
            duvida.descricao,
            categoria.id as categoria, 
            usuario.id as usuario, 
            duvida_status.id as status
        from
            duvida 
            inner join categoria 
                on duvida.categoria_id = categoria.id
            inner join usuario
                on duvida.usuario_id = usuario.id
            inner join duvida_status
                on duvida.status_id = duvida_status.id 
        order by duvida.id";       

        $stmt = Conexao::getInstance() -> prepare($sql);
        $stmt -> execute();
        while ($row = $stmt -> fetch()) {            
            array_push($duvidas, new Duvida($row['id'], $row['titulo'], $row['descricao'],$row['categoria'], $row['usuario'], $row['status']));
        }
        return $duvidas;

    } 

    public function list($id = -1){
        if($id == -1){
           return $this -> listAll();
        }else{
            
            return array($this -> listById($id));
        }
    }

}

class ListaDuvida{
    private $duvidas;

    function __construct($duvidas){        
        $this -> duvidas = $duvidas;
        //var_dump($this -> duvidas); 
    }

    function render(){
        $usuario = new Usuario();
        $status = new Status();
        $categoria = new Categoria();

        $this -> buffer = "<table border =\"1\">";
        $this -> buffer .= "  <tr>";
        $this -> buffer .= "      <th> Id </th>";
        $this -> buffer .= "      <th> Titulo </th>";
        $this -> buffer .= "      <th> Descricao </th>";
        $this -> buffer .= "      <th> Categoria </th>";
        $this -> buffer .= "      <th> Usuario </th>";
        $this -> buffer .= "      <th> Status </th>";
        $this -> buffer .= "  </tr>";
        foreach ($this -> duvidas -> list() as $duv) {
            $this -> buffer .= "  <tr>";
            $this -> buffer .= "      <td> {$duv -> getId()}  </td>";
            $this -> buffer .= "      <td> {$duv -> getTitulo()}</td>";
            $this -> buffer .= "      <td> {$duv -> getDescricao()}  </td>";
            $this -> buffer .= "      <td> {$categoria -> listById($duv -> getCategoriaId()) -> getDescricao()}  </td>";
            $this -> buffer .= "      <td> {$usuario -> listById($duv -> getusuarioId()) -> getNome()}  </td>";
            //$this -> buffer .= "      <td> {new Usuario-> listById($duv -> getUsuarioId()) -> getNome()}  </td>";
            $this -> buffer .= "      <td> {$status -> listById($duv -> getStatusId()) -> getDescricao()}  </td>";
            $this -> buffer .= "  </tr>";
        }
        $this -> buffer .= "</table>";
        
        return $this -> buffer;
    }
    
    function Show(){
        echo $this -> render();
    }
    
}
class ListagemDuvida{
    private $duvida;

    function __construct($duvida){ 
        $this -> duvida = $duvida;
    
    }

    function render(){
        $usuario = new Usuario();
        $status = new Status();
        $categoria = new Categoria();
        //var_dump($this->duvida);
        $this -> buffer ="<div class=\"card mb-4 box-shadow\"
        style=\"width: 327%; border-radius: 10px;box-shadow: 0px 1px 5px rgba(0, 0, 0, 0.22);text-align: center;\">";
        $this -> buffer .= "<div class=\"card-body\" style=\"text-align: left;\">";
        $this -> buffer .= "<h4 class=\"card-title pricing-card-title font-weight-bold mb-3\" style=\"text-align: left;\">{$this -> duvida -> getTitulo()}</h4>";
        $this -> buffer .= "<h6 class=\"card-title pricing-card-title font-weight-bold mb-3\" style=\"text-align: left;\"><img
              src=\"imagens/icons/usuario.png\" class=\"mr-3\" alt=\"\" > Por {$usuario -> listById($this -> duvida -> getusuarioId()) -> getNome()}</h6>";
        $this -> buffer .="<h6 class=\"card-title pricing-card-title mb-0\">{$this -> duvida -> getDescricao()}</h6>";
        $this -> buffer .= "<button type=\"button\" class=\"chip btn btn-primary btn-sm primary_color\" style=\"border-color: transparent; border-radius: 20px;height: 7%;font-size: 0.5em;  padding: 8px 16px 8px 16px; margin: 8px\">REDES</button>";
        $this -> buffer .= "<button type=\"button\" class=\"chip btn btn-primary btn-sm primary_color\" style=\"border-color: transparent; border-radius: 20px;height: 7%;font-size: 0.5em;  padding: 8px 16px 8px 16px;\">REDES</button>";
        $this -> buffer .= "</div>";
        $this -> buffer .="</div>";
        return $this -> buffer;
    }
    
    function Show(){
        echo $this -> render();
    }
    
}
class ListaCardDuvida{
    private $duvidas;

    function __construct($duvidas){        
        $this -> duvidas = $duvidas;
        //var_dump($this -> duvidas); 
    }

    function render(){
        $usuario = new Usuario();
        $status = new Status();
        $categoria = new Categoria();

        $this -> buffer = "";
        $i = 0;
        foreach ($this -> duvidas -> list() as $duv) {
            if($i % 2 == 0){
                $this -> buffer .= "</div>";
                $this -> buffer .="<div class=\"card-deck mb-3 text-center\">";
            }
            $card = new Card($duv -> getTitulo(), $duv -> getDescricao(), $link = "php/duvida_detalhe.php?id=".$duv -> getId());
            $this -> buffer .=  $card -> render();
            $i++;
        }
        
        
        return $this -> buffer;
    }
    
    function Show(){
        echo $this -> render();
    }
    
}

class ListaUsuario{
    private $usuarios;

    function __construct($usuarios){        
        $this -> usuarios = $usuarios;
        //var_dump($this -> usuarios); 
    }

    function render(){
        $this -> buffer = "<table border =\"1\">";
        $this -> buffer .= "  <tr>";
        $this -> buffer .= "      <th> Id </th>";
        $this -> buffer .= "      <th> Nome </th>";
        $this -> buffer .= "      <th> Email </th>";
        $this -> buffer .= "      <th> Senha </th>";
        $this -> buffer .= "      <th> Pontos de Bonificacao </th>";
        $this -> buffer .= "  </tr>";
        
        foreach ($this -> usuarios -> list() as $usuario) {
            $this -> buffer .= "  <tr>";
            $this -> buffer .= "      <td> {$usuario -> getId()}  </td>";
            $this -> buffer .= "      <td> {$usuario -> getNome()}</td>";
            $this -> buffer .= "      <td> {$usuario -> getEmail()}  </td>";
            $this -> buffer .= "      <td> {$usuario -> getSenha()}  </td>";
            $this -> buffer .= "      <td> {$usuario -> getPontosBonificacao()}  </td>";            
            $this -> buffer .= "  </tr>";
        }
        $this -> buffer .= "</table>";
        
        return $this -> buffer;
    }
    
    function Show(){
        echo $this -> render();
    }
}

class Card{
    private $titulo;
    private $texto;
    private $link;

    function __construct($titulo = "Título não definido", $texto = "texto não definido", $link = "#"){
        $this -> titulo = $titulo;
        $this -> texto = $texto;
        $this -> link = $link;
    }
    function render(){
        $this -> buffer = "<div class=\"card mb-4 \" style=\"text-align: left;background-color: white; height: 100%; border-radius: 10px;\">";
        $this -> buffer .= "<a href={$this -> link} style=\"text-decoration:none; color:black\">";
        $this -> buffer .= "    <div class=\"card-header\" style=\"background-color: transparent; border-color: transparent;\">";
        $this -> buffer .= "        <h4 class=\"my-0 font-weight-normal font-weight-bold\"><p>{$this -> titulo} </p></h4>";
        $this -> buffer .= "        <div class=\"card-body\" style=\"padding-top: 0;\">";
        $this -> buffer .= "            <!-- <h1 class=\"card-title pricing-card-title\">$0 <small class=\"text-muted\">/ mo</small></h1> -->";
        $this -> buffer .= "            <p>{$this -> texto}</p>";
        $this -> buffer .= "<button type=\"button\" class=\"chip btn btn-primary btn-sm primary_color\" style=\"border-color: transparent; border-radius: 20px;height: 7%;font-size: 0.5em;  padding: 8px 16px 8px 16px; margin-right: 8px\">REDES</button>";
        $this -> buffer .= "<button type=\"button\" class=\"btn btn-primary btn-sm primary_color\" style=\"border-color: transparent; border-radius: 20px;height: 7%;font-size: 0.5em;  padding: 8px 16px 8px 16px;\">MANUTENÇÃO</button>";
        $this -> buffer .= "</div>";
        $this -> buffer .= "</div>";
        $this -> buffer .= "</a>";
        $this -> buffer .= "</div>";

        return $this -> buffer;
    }
    
        function show(){
        echo $this -> render();
    }
}
   
    
      
      
      