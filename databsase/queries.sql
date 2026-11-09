-- Books currently on loan

SELECT
    l.loan_id,
    b.title,
    m.full_name,
    l.date_issued,
    l.due_date
FROM loans l
JOIN books b ON l.book_id = b.book_id
JOIN members m ON l.member_id = m.member_id
WHERE l.status = 'Borrowed';


-- Overdue books

SELECT
    b.title,
    m.full_name,
    l.due_date
FROM loans l
JOIN books b ON l.book_id = b.book_id
JOIN members m ON l.member_id = m.member_id
WHERE l.status = 'Overdue';