drop table if exists leaves;
drop table if exists employees;

create table employees(
  id varchar(37) not null primary key,
  username varchar(20) not null,
  password varchar(61) not null,
  firstName varchar(30) not null,
  lastName varchar(30) not null,
  status varchar(10) not null,
  leaves int not null,
  role varchar(10) not null,
  createdAt timestamp not null DEFAULT NOW(),
  updatedAt timestamp not null DEFAULT NOW() ON UPDATE NOW(),
  check (leaves >= 0)
);

create index username_idx on employees(username);

/*
 *  Seeded user password
 *
 *  username1 : password1
 *  username2 : password2
 *  username3 : password3
 *
 */

insert into employees(id, username, password, firstName, lastName, status, leaves, role) values
("5f6bd836-7151-4a36-8829-ab1fe0ee166c", "admin", "$2y$12$1qZldqE82OHyOZCXN27gmOufv.rwrzsvDbtUJc00AToFcTDURUSR2", "Admin", "Admin", "active", 10, "admin"),
("9c4a60c6-ce78-4fc1-9b15-d6770650003f", "username1", "$2y$12$/5aq/lXQzvnADrsevXFKnO9cki/gWB5e1nf7pklt.T6V..IZ24Ly2", "First", "FirstLast", "active", 10, "user"),
("82918cb1-6378-4113-8dcf-2cae12e9c8db", "username2", "$2y$12$iN1lHq955.t8DgsFFitffeA.3KYhvDK4PMhRjLj77eyA90uJpjoNO", "Second", "SecondLast", "active", 10, "user"),
("e9d20135-0704-45b9-b2b3-48fb294b9e33", "username3", "$2y$12$ogFPF8vnfOUOSZn.9rsBB.T9G/mQU984W6wyO2rns2R0bhL.GY3DC", "Third", "ThirdLast", "active", 10, "user");

drop table if exists leaves;
create table leaves(
  id varchar(37) not null primary key,
  employeeId varchar(37) not null,
  leaveDate date not null,
  status varchar(10) not null,
  updatedBy varchar(37),
  createdAt timestamp not null DEFAULT NOW(),
  updatedAt timestamp,
  foreign key(employeeId) references employees(id),
  foreign key(updatedBy) references employees(id)
);

create index employeeId_idx on leaves(employeeId);
create index updatedBy_idx on leaves(updatedBy);
create index status_idx on leaves(status);
create index createdAt_idx on leaves(createdAt);
create index leaveDate_idx on leaves(leaveDate);
create index employeeId_status_idx on leaves(employeeId, status);

insert into leaves(id, employeeId, leaveDate, status) values('05413ed1-ce85-48a5-b6d2-98b1d6a78384', '9c4a60c6-ce78-4fc1-9b15-d6770650003f', '2020-03-20', 'PENDING');
