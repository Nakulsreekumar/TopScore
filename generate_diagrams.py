import base64
import zlib
import urllib.request
import os

def generate_kroki_url(diagram_type, diagram_text):
    data = diagram_text.encode('utf-8')
    compressed = zlib.compress(data, 9)
    encoded = base64.urlsafe_b64encode(compressed).decode('utf-8')
    return f"https://kroki.io/{diagram_type}/png/{encoded}"

def download_diagram(diagram_type, diagram_text, output_file):
    url = generate_kroki_url(diagram_type, diagram_text)
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req) as response, open(output_file, 'wb') as out_file:
            data = response.read()
            out_file.write(data)
        print(f"Successfully generated {output_file}")
    except Exception as e:
        print(f"Error generating {output_file}: {e}")

# 1. Use Case Diagram
use_case = """
@startuml
left to right direction
actor "Administrator" as admin
actor "Teacher" as teacher
actor "Student" as student

package "Top Score Quiz System" {
  usecase "Manage Users" as UC1
  
  usecase "Create Quiz" as UC3
  usecase "Manage Questions" as UC4
  usecase "View Student Results" as UC5
  
  usecase "Join Quiz" as UC6
  usecase "Take Assessment" as UC7
  usecase "View Own Results" as UC8
}

admin --> UC1

teacher --> UC3
teacher --> UC4
teacher --> UC5

student --> UC6
student --> UC7
student --> UC8
@enduml
"""

# 2. Activity Diagram: Teacher Create Quiz
activity_teacher = """
@startuml
start
:Teacher Logs In;
:Access Dashboard;
:Click "Create Quiz";
:Enter Title and Duration;
:Generate Unique Access Code;
if (Add Questions?) then (Manual)
  :Enter MCQ/True-False;
else (CSV Upload)
  :Upload CSV File;
  :Parse and Validate Questions;
endif
:Publish Quiz;
:Share Access Code with Students;
stop
@enduml
"""

# 3. Activity Diagram: Student Take Quiz
activity_student = """
@startuml
start
:Student Logs In;
:Enter Access Code;
if (Code Valid?) then (Yes)
  :Join Quiz;
  :Initialize Timer;
  repeat
    :Answer Question;
    :Navigate to Next;
  repeat while (Timer Active & Not Submitted)
  :Submit Quiz;
  :Auto-Grade Answers;
  :Display Results;
else (No)
  :Show Error Message;
endif
stop
@enduml
"""

# 4. Sequence Diagram: Quiz Submission
sequence = """
@startuml
actor Student
participant "QuizController" as QC
participant "Question Model" as QM
participant "Result Model" as RM
database "Database" as DB

Student -> QC: Submit Answers
activate QC
QC -> QM: Fetch Correct Answers
activate QM
QM -> DB: Query Questions
activate DB
DB --> QM: Return Questions Data
deactivate DB
QM --> QC: Correct Answers List
deactivate QM

QC -> QC: Calculate Score
QC -> RM: Create Result Entry
activate RM
RM -> DB: Save Score
activate DB
DB --> RM: Success
deactivate DB
RM --> QC: Result ID
deactivate RM

QC --> Student: Redirect to Results
deactivate QC
@enduml
"""

# 5. UI Mockup: Login
ui_login = """
@startsalt
{+
  <b>Top Score Quiz System
  ==
  Login
  --
  Email: | "student@example.com"
  Password: | "*******"
  [ Login ]
  ..
  "Don't have an account? Register"
}
@endsalt
"""

# 6. UI Mockup: Student Dashboard
ui_student = """
@startsalt
{+
  <b>Top Score - Student Dashboard | [ Logout ]
  ==
  Welcome, Nakul Sreekumar!
  --
  {
    <b>Join a Quiz
    Enter Access Code: | "X7B9-QZ"
    [ Join ]
  }
  --
  <b>Your Recent Results
  {#
    Quiz Name | Score | Grade
    Math Test | 8/10 | B
    Science Quiz | 10/10 | A
  }
}
@endsalt
"""

# 7. UI Mockup: Teacher Quiz Creation
ui_teacher = """
@startsalt
{+
  <b>Top Score - Teacher Dashboard | [ Logout ]
  ==
  <b>Create New Quiz
  --
  Quiz Title: | "Midterm History Quiz"
  Duration: | "30 mins"
  [ Generate Access Code ]
  ..
  <b>Add Questions
  (X) Manual Entry  () CSV Upload
  --
  Question Text: | "What year did WW2 end?"
  [x] Option A: 1945
  [ ] Option B: 1939
  [ Add Question ]
  --
  [ Publish Quiz ]
}
@endsalt
"""

out_dir = r'e:\topscore\TopScore_FSD_Report'

download_diagram('plantuml', use_case, f'{out_dir}/UseCase_Diagram.png')
download_diagram('plantuml', activity_teacher, f'{out_dir}/Teacher_Create_Quiz_Activity.png')
download_diagram('plantuml', activity_student, f'{out_dir}/Student_Take_Quiz_Activity.png')
download_diagram('plantuml', sequence, f'{out_dir}/Quiz_Submission_Sequence.png')
download_diagram('plantuml', ui_login, f'{out_dir}/login_interface.png')
download_diagram('plantuml', ui_student, f'{out_dir}/student_dashboard.png')
download_diagram('plantuml', ui_teacher, f'{out_dir}/teacher_quiz_creation.png')

print("All diagrams created successfully.")
