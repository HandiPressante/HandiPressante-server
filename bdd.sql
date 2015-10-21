SET foreign_key_checks = 0;

DROP TABLE IF EXISTS toilettes;
DROP TABLE IF EXISTS images;

SET foreign_key_checks = 1;

CREATE TABLE images(
	id INT references toilettes(id)
  ,image varchar (60) PRIMARY KEY
);

INSERT INTO images(id,image) VALUES (80,'./images/1.jpeg');

CREATE TABLE toilettes(
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
  ,nom VARCHAR(40)
  ,lieu VARCHAR(60)
  ,horaires VARCHAR(100)
  ,pmr VARCHAR(50)
  ,x93 double 
  ,y93 double
);

CREATE INDEX index_x93 ON toilettes (x93);
CREATE INDEX index_y93 ON toilettes (y93);

INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'JARDINS FAMILIAUX BINTINAIS','Départemental 82','de Mars à Septembre',NULL,'352914.52','6785627.76');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MAIRIE OPERA','Place de la Mairie','du Lundi au Samedi 8h à 19h40.','NON','351996.02','6789296.02');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'HALLES CENTRALES','2 place Honoré Commeurec','de 7h à 19h sauf le dimanche','NON','351904.30','6788944.86');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'CHAMPS LIBRES','Cours des Alliers','du M au V de 12h à 19h, sauf mardi  21h; les S et D 14h à 19h.','OUI','352291.20','6788626.93');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'FREVILLE','Place de la Communauté',NULL,'OUI','352149.20','6787240.74');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GARE ROUTIERE','Bd Solférino',NULL,NULL,'352511.39','6788449.03');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'KLEBER_2','1 rue Kléber','Horaires du parking','NON','352364.81','6789220.63');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GARE','Place de la Gare','6h à 23h','OUI','352370.34','6788501.64');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'KLEBER parking','1 rue Kléber','Horaires du parking','NON','352377.15','6789221.47');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','Parc des Gayeuilles SUD "Tatelin"',NULL,NULL,'354845.89','6791398.35');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','Parc des Gayeuilles SUD "patinoire"',NULL,NULL,'354510.78','6791373.25');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GUY HOUIST','Sq. Guy Houist',NULL,'NON','351168.24','6789553.46');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'SAINT-CYR','61 quai St. Cyr','6h à 23h','OUI','350887.77','6788986.64');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MOULIN du COMTE','1 Sq. Simone Morand','6h à 23h','OUI','349827.22','6789164.42');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','rue Maurice AUDIN',NULL,NULL,'354609.75','6792070.14');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'CIMETIERE NORD','35 Rue Victor Ségalen','Horaires du Cimetière','NON','351864.41','6790818.39');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Saint MARTIN Ecluse','10 Canal St. Martin parking','6h à 23h','OUI','351867.31','6790389.04');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'PARC des TANNEURS','6 rue St Martin',NULL,NULL,'352051.99','6790214.13');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'PREFECTURE','3 av de la Préfecture','Horaires Préfecture','OUI','350969.80','6791164.58');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','40 Ave des Gayeulles',NULL,'OUI','354155.23','6791883.31');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','Chemin Mazé',NULL,NULL,'355213.45','6792303.83');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PARC','Jardins familliaux des Gayeulles',NULL,NULL,'355351.77','6792015.10');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'COURTELINE','Rue Courteline',NULL,NULL,'353819.64','6790645.60');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MAIL MITTERRAND','Mail Francois Mitterrand',NULL,NULL,'351054.89','6789123.38');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MAUREPAS Parc','Face 16 bd. Raymond Poincaré','Horaires du Parc','OUI','353882.58','6790400.23');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Cimetière Saint LAURENT','Face 29 rue de St. Laurent','Horaires du Cimetière','OUI','354012.26','6792202.54');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'CIMETIERE EST','85 Bd. Villebois Mareuil','Horaires du Cimetière','OUI','354163.77','6788294.57');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'SOUVENIR','Place du Souvenir Face 13 E. d''Orves','6h à 23h','OUI','353056.89','6787348.46');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Z.A.C. St.HELIER','Adolphe Leray',NULL,'OUI','353198.48','6788239.31');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'SAINTE THERESE EGLISE','Place Hyacinte Perrin Eglise','6h à 23h','OUI','352753.63','6787445.92');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'VILLENEUVE','Square de Villeneuve 1 r M. Berthelot','Horaires du Parc','OUI','351582.55','6787822.53');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'PATTON','Bd. Armorique',NULL,NULL,'353025.40','6791366.36');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'METRO CLEMENCEAU','1 Ave Fréville','6h à 23h','OUI','352241.42','6787310.16');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BIR-HAKEIM','2 place Bir-Hakeim','6h à 23h','OUI','352740.02','6787188.31');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GARE Intérieur','Esplanade Fulgence Bienvenue','du L au S 6h25 à 22h50 et le Dimanche 10h à 21h50','OUI','352388.29','6788313.98');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'POLYCLINIQUE LA SAGESSE','4 place Guénolé','Heures de la clinique','OUI','349379.25','6788271.67');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Jean GUY','12 rue Jean Guy','Horaires du parc','OUI','350717.85','6788880.17');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Marché CLEUNAY','Allée Joseph Germains','6h à 23h','OUI','349927.50','6788235.74');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ABELARD','Parking 1 rue Pierre Abélard','Horaires du parking','NON','351365.65','6788631.33');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'HOPITAL PONCHAILLOU','rue Henri Le Guilloux','24H / 24H','OUI','350919.19','6790207.77');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Dalle Kennedy','à l''intérieur du bâtiment',NULL,NULL,'349595.82','6790599.84');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'VILLEJEAN PARC','PARC de Villejean','TLJ','OUI','348905.59','6790733.98');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'COURROUZE','La Courrouze',NULL,NULL,'350490.19','6788183.56');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'HAUTES OURMES PARC','Parc des Hautes Ourmes','Horaires du Parc',NULL,'354356.17','6786731.42');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Bibliotheque Villejean','à l''intérieur bibliothèque',NULL,NULL,'349673.66','6790538.60');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'La TOUCHE Parc','Côté rue Maréchal Lyautey','Horaires du Parc','OUI','350966.21','6789940.71');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'VILLEJEAN PISCINE','1 Sq.d''Alsace','Horaires piscine','OUI','350160.97','6790641.53');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'VILLEJEAN BOURGOGNE','Rue de Bourgogne','6h à 23h','OUI','349922.93','6790322.92');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BERRY','Parc du Berry','6h à 23h','OUI','349770.36','6790244.93');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Eugene AULNETTE','Place Eugène Aulnette','6h à 23h',NULL,'350670.07','6791448.50');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'VILLEJEAN KENNEDY','Dalle piétons Kennedy','6h à 23h','OUI','349679.88','6790588.56');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BEAUREGARD PARC','Mail Emmanuel Le Ray','6h à 23h','OUI','350344.88','6791672.64');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'FERME du LANDRY','198 Rue de Châteaugiron le Parc','TLJ','OUI','354582.84','6787481.26');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'POTERIE','22 Place du Ronceray','6h à 23h','OUI','354656.51','6786886.59');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ALEXIS CARELL marché','Face 65 Bd. Alexis Carrel','6h à 23h',NULL,'353844.04','6790203.07');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'PATIS TATELIN','Rue du Patis Tatelin',NULL,NULL,'355114.32','6791789.50');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Jeanne d''ARC','5 Bd. Alexis Carrel Eglise','6h à 23h','OUI','353693.52','6789793.56');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLE','Rue du Professeur Maurice','TLJ',NULL,'354590.22','6791808.61');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEUILLES Resto','Parc des Gayeulles','Horaires du Parc','OUI','354848.48','6791560.99');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BERNANOS','Place G. Bernanos sud','6h à 23h','OUI','353474.58','6790581.40');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PISCINE','16 Ave des Gayeulles','Horaires piscine',NULL,'354231.14','6791772.07');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'GAYEULLES PATINOIRE','8 Ave des Gayeulles','TLJ vacances scolaires, et fermée les L, Ma, J, V, matin',NULL,'354179.27','6791542.70');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ERLANGEN','Place Erlangen sud','6h à 23h','OUI','353532.26','6791325.99');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ROBIDOU','Face 28 rue Bertrand Robidou','6h à 23h','OUI','353448.70','6788828.34');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Saint-GEORGES','Jardin St. Georges',NULL,NULL,'352302.05','6789321.85');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Haut des LICES 1 & 2','Place du Haut des LICES','n1 Jours de marchés & n2 TLJ',NULL,'351711.66','6789535.52');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MAIRIE centrale','Place de la Mairie','du L au V 8h30 à 17h, le S de 9h30 à 12h','NON','351910.90','6789344.98');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MAC MAHON','Bd de VERDUN',NULL,NULL,'351296.82','6790077.73');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'PARC OBERTHUR','Parc Hamelin Oberthür','Horaires du Parc','NON','353484.29','6789357.70');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Thabord GRIGNAN','1 rue de Grignan','Horaires du Parc','OUI','352557.91','6789737.44');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Thabord NOTRE DAME','1 place St. Melaine parc','Horaires du Parc','NON','352398.37','6789648.62');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Thabord Parc','Coté Bd. de la Duchesse Anne','Horaires du Parc','OUI','352947.86','6789538.34');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Thabord PARIS','1 rue de Paris','Horaires du Parc','NON','352589.17','6789381.04');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'DUCHESSE ANNE','Face au 3 Bd. de la Duchesse Anne','8h15 à 16h15','NON','352946.90','6789420.03');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Esplanade G. De GAULLE','Esplanade Gl. De Gaulle','6h à 23h','OUI','352070.53','6788770.42');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'HOCHE','Face au 10 place Hoche et sous-terrain','8h à 1h45','OUI','352144.24','6789737.44');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Saint GERMAINS','2 place St. Germains',NULL,'OUI','352174.47','6789193.87');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Sainte ANNE','Face au 15 place Ste.Anne',NULL,'OUI','351858.05','6789661.85');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Place de BRETAGNE','8 place de Bretagne','6h à 23h','OUI','351603.51','6789068.97');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'REPUBLIQUE','Face 1 Quai Lamennais','24h / 24h','OUI','351861.03','6789173.05');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Colombia','rez de chaussé Colombia',NULL,NULL,'351755.35','6788591.16');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'APIGNE','Parking 2 Chemin rural 72','TLJ','OUI','346889.14','6787621.36');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'APIGNE','Parking 1 Chemin rural 72','TLJ','OUI','347364.77','6787451.06');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'JUILLET','Face au 5 rue de Juillet','6h à 23h','NON','351584.76','6789451.29');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Charles GENIAUX','Square Charles Géniaux',NULL,NULL,'349536.02','6789454.81');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MITTERRAND','Mail Mitterrand',NULL,NULL,'351299.99','6789185.22');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'COLOMBIER','22 place du Colombier','6h à 23h','OUI','352001.87','6788601.12');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Salle / CITE','10 rue St. Louis','Horaires de la salle','NON','351767.77','6789666.63');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'T.N.B.','rue Saint-Hélier','Horaires du théâtre','OUI','352434.81','6788878.89');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'TOULLIER','2 rue Toullier','6h à 23h','NON','352244.66','6789071.65');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'MUSEE BEAUX ARTS','32 rue Hoches',NULL,NULL,'352139.10','6789645.02');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Le LIBERTE','1 Pl. Esplanade Gl. De Gaulle','Horaires de la salle',NULL,'352103.87','6788787.25');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Parc St CYR','Rue Papu',NULL,NULL,'350513.77','6789371.82');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Musée des Beaux-Arts','20 quai Emile Zola',NULL,NULL,'352267.27','6789102.18');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BREQUIGNY Piscine','Bd. Albert Premier','6h à 23h','OUI','351090.03','6786877.95');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'SARAH BERNHARDT','Square Sarah Bernhardt','Jour de marché','NON','351687.72','6787109.44');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'CHAMP MANCEAU','Rue Louis et René Moines','6h à 23h','OUI','351587.30','6787115.70');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BREQUIGNY Parc','Allée Ukraine côté Novotel','6h à 23h','OUI','351508.85','6786007.61');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ALBERT BAYET','Place Albert Bayet','6h à 23h','OUI','350819.07','6786508.12');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BREQUIGNY Angleterre','84 rue d''Angleterre',NULL,NULL,'350856.08','6786228.23');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'BLOSNE','Espace Social Ty-Blosne 7 Bd. Yougoslavie','du L au V de 8h30 à 18h, sauf mardi matin',NULL,'353376.06','6786674.45');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Le TRIANGLE','1 bd de Yougoslavie','Horaires de l''établissement',NULL,'353248.54','6786711.56');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ITALIE','Avenue d''Italie',NULL,NULL,'352672.10','6786608.05');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'Paul LAFARGUE','49 avenue des Pays Bas','6h à 23h','OUI','353163.33','6786149.92');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'HOPITAL SUD','16 bd de Bulgarie',NULL,'OUI','353711.15','6786096.30');
INSERT INTO toilettes(id,nom,lieu,horaires,pmr,x93,y93) VALUES (NULL,'ZAGREB','Place de Zagreb','Samedi jour de marché','OUI','353496.36','6786637.74');

