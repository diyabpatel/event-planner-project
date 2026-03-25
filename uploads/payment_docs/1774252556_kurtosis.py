# Import libraries
import pandas as pd
import numpy as np
import seaborn as sns
import matplotlib.pyplot as plt
from scipy.stats import kurtosis

# Load dataset
df = pd.read_csv('C:/Users/AJAY/OneDrive/Desktop/DIXITA/python_project/student_mentalhealth_productivity4.csv')

# Select numeric columns
numeric_cols = df.select_dtypes(include=np.number).columns

print("----- Kurtosis Analysis -----\n")

# Loop through each numeric column
for col in numeric_cols:
    
    # Remove missing values
    data = df[col].dropna()
    
    # Calculate kurtosis
    kurt_value = kurtosis(data)
    
    # Determine distribution type
    if kurt_value > 0:
        distribution = "Leptokurtic (Highly peaked)"
    elif kurt_value < 0:
        distribution = "Platykurtic (Flat)"
    else:
        distribution = "Mesokurtic (Normal)"
    
    # Print results
    print(f"{col}")
    print(f"Kurtosis Value: {kurt_value:.2f}")
    print(f"Distribution Type: {distribution}")
    print("-----------------------------------")
    
    # Plot histogram with KDE
    plt.figure(figsize=(6,4))
    sns.histplot(data, kde=True, color='orange')
    
    plt.title(f"{col} Distribution\nKurtosis = {kurt_value:.2f}")
    plt.xlabel(col)
    plt.ylabel("Frequency")
    
    plt.show()
