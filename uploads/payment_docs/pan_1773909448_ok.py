import pandas as pd
import numpy as np
import random

np.random.seed(42)

records = []

departments = ['BCA','BBA','Bsc','BA','BTech','MBA','Msc','MCOM','MCA','MBBS','Diploma','MTech']
genders = ['Male','Female']

for i in range(1,301):

    study = np.random.randint(1,11)
    sleep = np.random.randint(4,10)
    screen = np.random.randint(1,10)
    attendance = np.random.randint(60,101)
    cgpa = round(np.random.uniform(5.5,9.8),2)

    stress = np.random.randint(1,11)
    anxiety = np.random.randint(1,11)
    depression = np.random.randint(1,11)

    # Add base constant to shift range upward
    productivity = (
        30                                   # base shift
        + 4.5*study
        + 0.4*attendance
        + 3.5*cgpa
        + 2*sleep
        - 3*stress
        - 2.5*anxiety
        - 2.5*depression
        - 2*screen
        + np.random.normal(0,1)               # small noise
    )

    productivity = int(round(productivity))

    # Soft boundary control
    if productivity < 45:
        productivity = 45 + np.random.randint(0,5)
    if productivity > 97:
        productivity = 97 - np.random.randint(0,5)

    records.append([
        i,
        f"Student_{i}",
        np.random.randint(17,25),
        random.choice(genders),
        random.choice(departments),
        np.random.randint(1,4),
        study,
        sleep,
        screen,
        attendance,
        cgpa,
        stress,
        anxiety,
        depression,
        productivity
    ])

columns = [
    "Id","Name","Age","Gender","College_Department","College_Year",
    "Study_Hours","Sleep_Hours","Screen_Time","Attendance","CGPA",
    "Stress_Level","Anxiety_Score","Depression_Score","Productivity_Score"
]

df = pd.DataFrame(records, columns=columns)

df.to_csv("C:/Users/AJAY/OneDrive/Desktop/DIXITA/python_project/student_mentalhealth_productivity_raw4.csv", index=False)

print("Dataset created successfully.")
print("Min Productivity:", df["Productivity_Score"].min())
print("Max Productivity:", df["Productivity_Score"].max())
