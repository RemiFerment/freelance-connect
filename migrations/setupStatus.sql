INSERT INTO mission_status (`label`,`code`)
VALUES
("En attente","PENDING"),
("En cours","IN_PROGRESS"),
("Terminée","COMPLETED"),
("Annulée","CANCELED");

INSERT INTO candidacy_status (`label`,`code`)
VALUES
("En attente","PENDING"),
("Acceptée","ACCEPTED"),
("Refusée","REFUSED");

INSERT INTO invoice_status (`label`,`code`)
VALUES
("En attente","PENDING"),
("Payé","PAID");

INSERT INTO category (`label`)
VALUES
("Design"),
("Développement"),
("Marketing"),
("Consulting");

INSERT INTO language (`label`)
VALUES
("Français"),
("Anglais"),
("Espagnol"),
("Allemand");

