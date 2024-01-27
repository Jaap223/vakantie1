create table vakanties (
    vakantieid int primary key auto_increment,
    bestemming varchar(255) not null,
    vertrekdatum date not null,
    terugkeerdatum date not null,
    prijs decimal(10, 2) not null
);

CREATE TABLE users (
    userid INT(10) PRIMARY KEY AUTO_INCREMENT,
    naam VARCHAR(255),
    adres VARCHAR(255),
    wachtwoord VARCHAR(255),
    tel_nr VARCHAR(10)
);

create table boekingen (
    boekingid int primary key auto_increment,
    klantid int,
    vakantieid int,
    boekingsdatum date not null,
    aantalpersonen int not null,
    foreign key (klantid) references users(userid),
    foreign key (vakantieid) references vakanties(vakantieid)
);

create table betalingen (
    betalingid int primary key auto_increment,
    boekingid int,
    bedrag decimal(10, 2) not null,
    betaaldatum date not null,
    betaalmethode varchar(50) not null,
    foreign key (boekingid) references boekingen(boekingid)
);
