DROP TABLE IF EXISTS api_token;
DROP TABLE IF EXISTS pawns;
DROP TABLE IF EXISTS game;
DROP TABLE IF EXISTS persons;

CREATE TABLE persons (
  player_id bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  user_name varchar(255) NOT NULL,
  PRIMARY KEY (player_id),
  UNIQUE KEY user_name (user_name)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE game (
  game_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  player1_id bigint(20) unsigned NOT NULL,
  player2_id bigint(20) unsigned NOT NULL,
  status varchar(255) NOT NULL,
  start_time timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (game_id),
  KEY player1_id (player1_id),
  KEY player2_id (player2_id),
  CONSTRAINT game_ibfk_1 FOREIGN KEY (player1_id) REFERENCES persons (player_id),
  CONSTRAINT game_ibfk_2 FOREIGN KEY (player2_id) REFERENCES persons (player_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE pawns (
  pawn_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  game_id bigint(20) unsigned NOT NULL,
  x tinyint(1) unsigned NOT NULL,
  y tinyint(1) unsigned NOT NULL,
  color enum ('Red','Blue') NOT NULL,
  nr tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (pawn_id),
  UNIQUE KEY pawns_positon_taken (game_id, x, y) USING BTREE,
  UNIQUE KEY pawns_order (game_id, nr) USING BTREE,
  CONSTRAINT pawns_ibfk_1 FOREIGN KEY (game_id) REFERENCES game (game_id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8;

CREATE TABLE api_token (
  token char(32) NOT NULL,
  player_id bigint(20) unsigned NOT NULL,
  PRIMARY KEY (token),
  KEY player_id (player_id),
  CONSTRAINT api_token_ibfk_1 FOREIGN KEY (player_id) REFERENCES persons (player_id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8;
