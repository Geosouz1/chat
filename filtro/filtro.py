
from PIL import Image
img = Image.open('perigo.jpg')
r,g,b = img.split()

from random import randint

valor=randint(0,10)
valorg=randint(0,5)

lutr =[]

for i in range (256):
        if i <= 10:
                lutr.append(i*valor)
        elif i > 10:
                lutr.append(i*2)
        elif i > 200:
                lutr.append(i-valor)
r2 = r.point(lutr)

lutg =[]
for i in range (256):
        if i <= 10:
                lutg.append(i*2)
        elif i > 10:
                lutg.append(i*valorg)
        elif i > 200:
                lutg.append(i-valor)
g2 = g.point(lutg)

lutb =[]
for i in range (256):
        if i <= 10:
                lutb.append(i+2)
        elif i > 10:
                lutb.append(i+10)
        elif i > 200:
                lutb.append(i-180)
b2 = b.point(lutb)

res = Image.merge('RGB',(r2,g2,b2))


res.save('res.jpg')
