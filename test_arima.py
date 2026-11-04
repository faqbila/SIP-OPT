import pandas as pd
import matplotlib
matplotlib.use('TkAgg')
import matplotlib.pyplot as plt
from statsmodels.graphics.tsaplots import plot_acf, plot_pacf
from statsmodels.tsa.stattools import adfuller
from statsmodels.tsa.arima.model import ARIMA

# =========================
# DATA
# =========================
data = [
    981.47, 1688.05, 2794.14, 2996.88, 2057.03, 1680.1, 2650.13,
    1516.98, 2777.2, 1493.03, 2167.29, 2161.49, 638.56,
    4015.95, 3985.17, 3887.06, 4118.51, 4417.43, 5728.4,
    4991.42, 4629.87, 1381.38, 2434.49, 3643.83, 2920.01,
    3217.66, 3297.42, 2962.91, 2554.22
]

df = pd.DataFrame(data, columns=['value'])

# =========================
# UJI STASIONERITAS (ADF)
# =========================
print("=== UJI STASIONERITAS (ADF TEST) ===")

result = adfuller(df['value'])

print(f"ADF Statistic : {result[0]}")
print(f"p-value       : {result[1]}")

if result[1] <= 0.05:
    print("KESIMPULAN: Data STASIONER\n")
    data_used = df['value']
    d = 0
else:
    print("KESIMPULAN: Data TIDAK STASIONER → differencing\n")
    
    df['diff'] = df['value'].diff()
    data_used = df['diff'].dropna()
    d = 1

# =========================
# VISUALISASI
# =========================
plt.figure()
plt.plot(data_used)
plt.title("Data Time Series")

plt.figure()
plot_acf(data_used, lags=15)
plt.title("ACF")

plt.figure()
plot_pacf(data_used, lags=10)
plt.title("PACF")

# =========================
# INPUT PARAMETER MANUAL
# =========================
p = 1   # dari PACF
q = 1   # dari ACF

print(f"\nModel yang digunakan: ARIMA({p},{d},{q})")

# =========================
# MODEL ARIMA
# =========================
model = ARIMA(df['value'], order=(p, d, q))
model_fit = model.fit()

print(model_fit.summary())

# =========================
# FORECAST
# =========================
forecast = model_fit.forecast(steps=5)

plt.figure()
plt.plot(df['value'], label='Data Asli')
plt.plot(range(len(df), len(df)+5), forecast, label='Forecast')
plt.legend()

plt.show()

# =========================
# OUTPUT
# =========================
print("\nHasil Forecast:")
for i, val in enumerate(forecast):
    print(f"Periode {i+1}: {val}")