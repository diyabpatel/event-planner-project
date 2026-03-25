# =====================================================
# PROFESSIONAL DATA CLEANING PIPELINE
# Student Mental Health Dataset
# =====================================================

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# =====================================================
# STEP 1: Load Dataset
# =====================================================

file_path = 'C:/Users/AJAY/OneDrive/Desktop/DIXITA/python_project/student_mentalhealth_productivity_raw4.csv'
df = pd.read_csv(file_path)

print("Dataset Loaded Successfully\n")

# =====================================================
# STEP 2: Basic Dataset Understanding
# =====================================================

print("Dataset Shape:", df.shape)

print("\nDataset Info:")
df.info()

print("\nStatistical Summary:")
print(df.describe())


# =====================================================
# STEP 3: Missing Value Detection
# =====================================================

print("\nMissing Values Count:")
print(df.isnull().sum())

# =====================================================
# 📊 MISSING VALUE BAR CHART (BEFORE HANDLING)
# =====================================================

missing_counts = df.isnull().sum()

if missing_counts.sum() == 0:
    print("\nNo missing values found in dataset.")
else:
    plt.figure()
    missing_counts[missing_counts > 0].plot(kind='bar')
    plt.title("Missing Values Count (Before Handling)")
    plt.ylabel("Number of Missing Values")
    plt.xticks(rotation=45)
    plt.show()


# =====================================================
# STEP 4: Handle Missing Values Automatically
# =====================================================

for col in df.columns:
    if df[col].dtype == 'object':
        df[col] = df[col].fillna(df[col].mode()[0])
    else:
        df[col] = df[col].fillna(df[col].median())

print("\nMissing Values After Handling:")
print(df.isnull().sum())


# =====================================================
# 📊 MISSING VALUE BAR CHART (AFTER HANDLING)
# =====================================================

missing_counts_after = df.isnull().sum()

plt.figure()
missing_counts_after.plot(kind='bar')
plt.title("Missing Values Count (After Handling)")
plt.ylabel("Number of Missing Values")
plt.xticks(rotation=45)
plt.show()


# =====================================================
# STEP 5: Detect Numerical Columns
# =====================================================

numeric_cols = df.select_dtypes(include=['int64', 'float64']).columns

print("\nNumeric Columns Detected:")
print(numeric_cols)


# =====================================================
# STEP 6: Visualization BEFORE Outlier Removal
# =====================================================

plt.figure()
df[numeric_cols].boxplot()
plt.xticks(rotation=45)
plt.title("Boxplot BEFORE Outlier Removal")
plt.show()


# =====================================================
# STEP 7: Outlier Detection & Treatment (IQR Method)
# =====================================================

for col in numeric_cols:
    
    Q1 = df[col].quantile(0.25)
    Q3 = df[col].quantile(0.75)
    IQR = Q3 - Q1
    
    lower_bound = Q1 - 1.5 * IQR
    upper_bound = Q3 + 1.5 * IQR
    
    outliers = ((df[col] < lower_bound) | (df[col] > upper_bound)).sum()
    
    print(f"\nOutliers detected in {col}: {outliers}")
    
    median = df[col].median()
    
    # Fix dtype issue
    if pd.api.types.is_integer_dtype(df[col]):
        median = int(round(median))
    
    df.loc[(df[col] < lower_bound) | (df[col] > upper_bound), col] = median


# =====================================================
# STEP 8: Visualization AFTER Outlier Removal
# =====================================================

plt.figure()
df[numeric_cols].boxplot()
plt.xticks(rotation=45)
plt.title("Boxplot AFTER Outlier Removal")
plt.show()


# =====================================================
# STEP 9: Save Cleaned Dataset
# =====================================================

cleaned_path = 'C:/Users/AJAY/OneDrive/Desktop/DIXITA/python_project/student_mentalhealth_productivity_cleaned.csv'
df.to_csv(cleaned_path, index=False)

print("\nCleaned dataset saved successfully.")
print("Saved at:", cleaned_path)

# =====================================================
# END
# =====================================================
