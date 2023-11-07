# Symfony To-Do List API

This is a simple Symfony-based API .

The directory POSTMAN contains a collection to check queries

API Endpoints

Create a Task
URL: http://symfonyproject/api/tasks
Method: POST
Request Body:
json
Copy code
{
"text": "task text"
}

Complete a Task
URL: http://symfonyproject/api/tasks/{id}/complete
Method: PUT

Delete a Task
URL: http://symfonyproject/api/tasks/{id}
Method: DELETE

Update a Task
URL: http://symfonyproject/api/tasks/{id}
Method: PUT
Request Body:
json
Copy code
{
"text": "Updated task text"
}

List Tasks with Pagination
URL: http://symfonyproject/api/tasks
Method: GET
Query Parameters:
page (optional): Page number
limit (optional): Number of tasks per page

Task Fields
completed: Task completion status
text: Task description
viewCount: Number of times the task has been viewed
status: Task status (new, viewed, important, completed)

Updated task text
URL: http://symfonyproject/api/tasks/{id}
Method: PUT
Request Body:
json
Copy code
{
"text": "Updated task text"
}

URL: http://symfonyproject/api/tasks/{id}/view
Method: GET
View Task
