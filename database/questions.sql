-- Here are some questions to answer
-- Keep the questions in the file, and just put the answers below the questions.

/*
  About the DATA
  There are 4 tables
  here is a list with descriptions

  IMPORTANT: YOU MAY CHANGE THE TABLE STRUCTURES IF YOU WOULD LIKE.
      THE LAST QUESTION WILL ASK ABOUT ALL YOUR CHANGES.

  - users
     - just a list of user data
  - emails
     - holds users emails.
     - There is a one to many relationship with the users table. Each user can have many emails
     - One email is marked as the primary email for the user
  - usage_log
     - holds the users session dates and times.
     - contains the login and logout times of every user session.
     - So every time a user logs in, it creates a new entry in this table
  - users_admin
     - only holds a user id
     - if a user's id is in this table, then they are an admin
*/

-- EXAMPLE
-- Write a statement that will return all the users
--  with the last name 'Johnson'
SELECT *
  FROM users
  WHERE lName = 'Johnson';


-- QUESTION 1
-- write a statement that returns all the users data
--   including their primary email, if they have one
--   and if they are an admin or not

select
u.*,
e.email as primary_email,
case when ua.id > 0 then true else false end as admin
from `users` u
left join `emails` e on e.`userId` = u.id and e.`primary` = 1
left join `user_admin` ua on ua.`userId` = u.`id`;

-- // --

-- QUESTION 2
-- write a statement that returns all user data
--   including their primary email
--   and if they are an admin or not
--   but only users with emails

select
u.*,
e.email as primary_email,
case when ua.id > 0 then true else false end as admin
from `users` u
INNER join `emails` e on e.`userId` = u.id and e.`primary` = 1
left join `user_admin` ua on ua.`userId` = u.`id`;

-- // --

-- QUESTION 3
-- write a statement that returns all user data
--   that do not have an email
--   and are not admins

select
u.*,
e.email as primary_email,
case when ua.id > 0 then true else false end as admin
from `users` u
left join `emails` e on e.`userId` = u.id
left join `user_admin` ua on ua.`userId` = u.`id`
where e.id is null
and ua.id is null;

-- // --

-- QUESTION 4
-- write a statement that returns all the users data
--    only users with last name that contains a letter 'B'
--    and also return the number of emails those users have

select u.*,
count(*) as total_emails
from `users` u
left join `emails` e on e.`userId` = u.id
where u.`lName` like '%B%'
group by u.id;

-- // --

-- QUESTION 5
-- write a statement that returns all the users data
--    only users that have more than one email
--    and are admins

select u.*,
count(*) as total_emails
from `users` u
left join `emails` e on e.`userId` = u.id
inner join `user_admin` ua on ua.`userId` = u.`id`
group by u.id
having count(*) > 1;

-- // --

-- QUESTION 6
-- write a statement that returns all user data
--   with the total amount of time the users have spent on the site
--   in the past 21 days, in minutes

select u.*,
case when sum(timestampdiff(MINUTE,login,logout)) is null then 0 else sum(timestampdiff(MINUTE,login,logout)) end as total_time
from `users` u
left join `usage_log` ul on ul.`userId` = u.`id` and logout > CURRENT_DATE - interval 21 day
group by u.id;

-- QUESTION 7
-- Write a statement that returns all user data
--   with the total amount of time spent on the site
--   and with the total number of logins
--   beginning of time

select u.*,
case when sum(timestampdiff(MINUTE,login,logout)) is null then 0 else sum(timestampdiff(MINUTE,login,logout)) end as total_time,
count(distinct(ul.id)) as total_logins
from `users` u
left join `usage_log` ul on ul.`userId` = u.`id`
group by u.id;

-- QUESTION 8
-- given the table structure provided.
-- How would you did/would you change/improve our schema? Any Why?
-- Please list all changes that were made and a justification for the change.

  * foreign keys for data integrity
  * as user_admin is [0,1 to 1] with users, a simple boolean column on the users table would have the same result with less structure
  * emails.`primary` as boolean instead of integer for more accuracy
  * created_at and updated_at columns to control those times independently
  * fName, lName and email NOT NULL to keep data consistent
  * sessionId as varchar for more flexibility and to meet standards (like PHPs session_id())
  * users.`login` with DEFAULT CURRENT_TIMESTAMP to make things easier
  * ....


