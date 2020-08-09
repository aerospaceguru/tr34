import math

# Calculation check according to TR34
# Loading case: main gear wheel
# C28/35 concrete
fck = 28
fcu = 35
fcm = 36
fctm = 2.8
Ecm = 32000
h = 205
yc = 1.5
ys = 1.15
yG = 1.2
yQ = 1.5
yD = 1.6
fyk = 500
As = 393
bar_dia = 10
cover = 30
d = (h - cover - bar_dia)
Qk = 157  # F15E MLG load # 114  # kN load of MLG of F35  
tp = 2.1  # Tyre pressure for F15E # 1.758  # Tyre pressure for F35B  
ta = 157000/tp  # Approx tyre contact area F15E # 54000 # MG contact area for F35B tyre in mm2  
li = math.sqrt(ta)  # single side of square contact area 
lw = math.sqrt(ta)
u0 = 2 * (li + lw)  # u0 = length  perimeter of loaded area as described in Section 7.8.1 of TR34
a = math.sqrt((ta / math.pi))  # equivalent contact radius of single load
u1 = 2 * (li + lw + (2 * d * math.pi))  # length of the perimeter at a distance 2d from face of load NEEDS CHECKING
k = 0.2
poisson = 0.2
N = 1

# For slabs thinner than 600mm flexural tensile strength is:
# fctd_fl = fctm * (1.6 – h/1000)/γm

fctd_fl = (3.0 * (1.6 - (h / 1000)) / 1.5)
print('fctdl_fl = ' + "%.2f" % fctd_fl + ' N/mm2')

# The moment capacity of plain concrete per unit width of slab is (hogging moment):
# Mun = fctd_fl*(h^2 / 6)

Mun = (fctd_fl * (pow(h, 2) / 6)) / 1000

# Moment capacity Mpfab of steel fabric reinforced concrete (sagging moment) per unit width of slab is calculated from:
# Mpfab = 0.95 * As * fyk * d / ym

Mpfab = (0.95 * (As) * fyk * d / ys) / (1000 * 1000)  # 1000*1000 converts from Nmm to kNm

# Shear at the face of the loaded area
fcd = fck / yc
k2 = 0.6 * (1 - (fck / 250))
vmax = 0.5 * k2 * fcd
Ppmax = vmax * u0 * d / 1000

# Shear on the critical perimeter (unreinforced)
ks = 1 + math.pow((200 / d), 0.5)
vrdc = 0.035*math.pow(ks, 1.5)*math.pow(fck, 0.5)

# # Shear on the critical perimeter (reinforced)
# rhoX = As / (1000 * d)
# rhoY = As / (1000 * d)
# rho1 = math.sqrt(rhoX * rhoY)
# ks = 1 + math.pow((200 / d), 0.5)
# if ks > 2:
#     ks = 2
# else:
#     ks = ks
# vrdc = (0.18 * ks) * math.pow((100 * rho1 * fck), 0.33)
# vrdc_min = 0.035 * math.pow(ks, 1.5) * math.pow(fck, 0.5)
# if vrdc >= vrdc_min:
#     print('vrdc OK')
# else:
#     print('vrdc FAIL')


Pp = vrdc * u1 * d / 1000

top = Ecm * math.pow(h, 3)
bottom = 12 * (1 - math.pow(poisson, 2)) * k
combined = top / bottom
l = math.pow(combined, 0.25)
print('l = ' + "%.2f" % l + ' mm')
rr = a/l
print('a/l (>0.2?) = ' + "%.2f" % rr)
print('\n')

Pu = (4 * math.pi * (Mun + Mun)) / (1 - (a / (3*l)))  # For a/l > 0.2 Also, replace Mpfab with Mun to check in scenario where no reinforcement present. # Pu = (4 * math.pi * (Mpfab + Mun)) / (1 - (a / (3*l)))  #

Fuls = N * Qk * yQ

print('Fuls = ' + "%.2f" % Fuls + ' kN')
print('Pu (ult. capacity single conc. internal load) = ' + "%.2f" % Pu + ' kN')
print('PPmax (punching shear capacity) = ' + "%.2f" % Ppmax + ' kN')
print('Pp (shear capacity critical perimeter) = ' + "%.2f" % Pp + ' kN')
print('Mpfab (Moment capacity steel fabric reinforced) = ' + "%.2f" % Mpfab + ' kNm/m')
print('Mun (Moment capacity un-reinforced) = ' + "%.2f" % Mun + ' kNm/m')
print('\n')
if (Fuls < Ppmax) and (Fuls < Pp) and (Fuls < Pu):
    print('PASSES')
else:
    print('FAILS')