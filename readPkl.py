import pandas as pd

df2 = pd.read_pickle('dfV2.pkl')
df3 = pd.read_pickle('dfV3.pkl')
dx = pd.DataFrame()
dx = pd.concat([df2, df3])
y = dx.drop_duplicates()
print(y)
# Generar csv del dataframe
y.to_pickle('dfFinal.pkl')
