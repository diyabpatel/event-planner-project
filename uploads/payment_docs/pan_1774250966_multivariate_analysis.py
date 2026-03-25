import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns

df = pd.read_csv('C:/Users/AJAY/OneDrive/Desktop/DIXITA/python_project/student_mentalhealth_productivity4.csv')

selected_columns = [
    'Study_Hours',
    'Sleep_Hours',
    'CGPA',
    'Stress_Level',
    'Productivity_Score'
]

sns.pairplot(df[selected_columns])
plt.title("Pairplot : ")
plt.show()

selected_columns = [
    'Study_Hours',
    'Sleep_Hours',
    'CGPA',
    'Stress_Level',
    'Productivity_Score'
]

corr_matrix = df[selected_columns].corr()
sns.heatmap(
    corr_matrix,
    annot=True,
    cmap='coolwarm',
    fmt=".2f"
)
plt.title("Correlation Heatmap : ")
plt.show()
