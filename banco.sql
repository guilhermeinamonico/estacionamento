CREATE TABLE tb_veiculo (
  idveiculo int(10) ,
  ano int(10) ,
  cor varchar(100) ,
  modelo varchar(100) ,
  placa varchar(12) ,
   CONSTRAINT pk_tb_veiculo PRIMARY KEY(idveiculo)
) 

CREATE TABLE tb_entradasaida(
	id_entradasaida int(10),
    idveiculo int(10),
    horaEntrada time,
    horaSaida time,
    hora time,
    preco decimal(9.2),
    CONSTRAINT pk_tb_entradasaida PRIMARY KEY (id_entradasaida),
    CONSTRAINT fk_tb_entradasaida_tb_veiculo FOREIGN KEY (idveiculo)
    REFERENCES tb_veiculo(idveiculo)

)



INSERT INTO tb_veiculo(placa,ano,cor,modelo) VALUES('BAN-9110',1995,'vermelho','corsa'),
											('LVJ-0797',2017,'Preto','corolla'),
                                            ('NCZ-8930',2005,'Preto','Gol'),
                                            ('MGJ-8887',1997,'Rosa','Opala'),
                                            ('NFA-0633',1978,'Amarelo','Fusca'),
                                            ('FYT-3871',1991,'Branca','Brasilia'),
                                            ('MUI-6154',2013,'vermelho','Golf'),
                                            ('BMC-4653',1998,'Preto','corsa'),
                                            ('BBO-3666',1995,'Azul','corsa'),
                                            ('NEU-0966',1995,'vermelho','corsa'),
                                            ('COJ-6155',1995,'vermelho','corsa'),
                                            ('MXF-5643',1995,'vermelho','corsa');



INSERT INTO tb_entradasaida(idveiculo,horaEntrada,horaSaida,hora,preco) VALUES(1,'20:15','21:10',(horaSaida-horaEntrada),hora*0.00003),
						(1,'20:15','21:10',(horaSaida-horaEntrada),hora*0.00003),
                        (3,'19:00','22:10',(horaSaida-horaEntrada),hora*0.00003),
                        (10,'15:15','16:20',(horaSaida-horaEntrada),hora*0.00003),
                        (9,'04:20','16:20',(horaSaida-horaEntrada),hora*0.00003),
                        (7,'07:','12:30',(horaSaida-horaEntrada),hora*0.00003);
												
