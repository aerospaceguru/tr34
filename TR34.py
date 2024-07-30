import math

###############################################################
#                                                             #
#  *** NB: UN-REINFORCED SOLUTION NEEDS REVIEW DO NOT USE *** #
#                                                             #
###############################################################



C25_30 = {'fck': 25, 'fcu': 30, 'fcm': 33, 'fctm': 2.6, 'Ecm': 31000}
C28_35 = {'fck': 28, 'fcu': 35, 'fcm': 36, 'fctm': 2.8, 'Ecm': 32000}
C30_37 = {'fck': 30, 'fcu': 37, 'fcm': 38, 'fctm': 2.9, 'Ecm': 33000}
C32_40 = {'fck': 32, 'fcu': 40, 'fcm': 40, 'fctm': 3.0, 'Ecm': 33000}
C35_45 = {'fck': 35, 'fcu': 45, 'fcm': 43, 'fctm': 3.2, 'Ecm': 34000}
C40_50 = {'fck': 40, 'fcu': 50, 'fcm': 48, 'fctm': 3.5, 'Ecm': 35000}


def tr34(conc, h, fyk, As, bar_dia, cover, Qk, tyre_area, k30, N, r):

    yc = 1.5
    ys = 1.15
    yQ = 1.5
    poisson = 0.2
    k30 = k30 / 1000

    d = (h - cover - bar_dia)
    li = math.sqrt(tyre_area)
    lw = math.sqrt(tyre_area)
    u0 = 2 * (li + lw)
    a = math.sqrt((tyre_area / math.pi))
    u1 = 2 * (li + lw + (2 * d * math.pi))
    print('--->     u1 = ' + "%.0f" % u1 + ' mm') 

    # For slabs thinner than 600mm flexural tensile strength is:
    # fctd_fl = fctm * (1.6 – h/1000)/γm
    fctd_fl = (3.0 * (1.6 - (h / 1000)) / 1.5)

    # The moment capacity of plain concrete per unit width of slab is (hogging moment):
    # Mun = fctd_fl*(h^2 / 6)
    Mun = (fctd_fl * (pow(h, 2) / 6)) / 1000

    # Moment capacity Mpfab of steel fabric reinforced concrete (sagging moment) per unit width of slab is calculated
    # from: Mpfab = 0.95 * As * fyk * d / ym
    Mpfab = (0.95 * As * fyk * d / ys) / (1000 * 1000)  # 1000*1000 converts from Nmm to kNm

    # Shear at the face of the loaded area
    fcd = conc['fck'] / yc
    k2 = 0.6 * (1 - (conc['fck'] / 250))
    vmax = 0.5 * k2 * fcd
    Ppmax = vmax * u0 * d / 1000

    # Shear on the critical perimeter (unreinforced)
    ks = 1 + math.pow((200 / d), 0.5)
    vrdc = 0.035 * math.pow(ks, 1.5) * math.pow(conc['fck'], 0.5)

    if r == 'r':
        # # Shear on the critical perimeter (reinforced)
        rhoX = As / (1000 * d)
        rhoY = As / (1000 * d)
        rho1 = math.sqrt(rhoX * rhoY)
        ks = 1 + math.pow((200 / d), 0.5)
        if ks > 2:
            ks = 2
        else:
            ks = ks
        vrdc = (0.18 * ks) * math.pow((100 * rho1 * conc['fck']), 0.33)
        print('--->     vrdc = ' + "%.3f" % vrdc + ' N/mm2')
        vrdc_min = 0.035 * math.pow(ks, 1.5) * math.pow(conc['fck'], 0.5)
        print('--->     vrdc_min = ' + "%.3f" % vrdc_min + ' N/mm2')
        if vrdc >= vrdc_min:
            print('\n--->     vrdc OK')
        else:
            print('\n--->     vrdc FAIL')
        print('--->     *** REINFORCED ***')
        print('--->     ks = '+ "%.2f" % ks)
    elif r == 'ur':
        # Shear on the critical perimeter (unreinforced)
        ks = 1 + math.pow((200 / d), 0.5)
        vrdc = 0.035 * math.pow(ks, 1.5) * math.pow(conc['fck'], 0.5)
        print('\n--->     *** UN-REINFORCED ***')

    # Shear capacity critical perimeter
    Pp = vrdc_min * u1 * d / 1000

    top = conc['Ecm'] * math.pow(h, 3)
    bottom = 12 * (1 - math.pow(poisson, 2)) * k30
    combined = top / bottom
    l = math.pow(combined, 0.25)
    print('--->     l = ' + "%.2f" % l + ' mm')
    rr = a / l

    if r == 'r':
        Pu = (4 * math.pi * (Mpfab + Mun)) / (1 - (a / (3 * l)))

    elif r == 'ur':
        Pu = (4 * math.pi * (Mun + Mun)) / (1 - (a / (3 * l)))

    # Ultimate limit state
    Fuls = N * Qk * yQ

    print('--->     a/l > 0.2 = ' + "%.2f" % rr)
    print('--->     Fuls = ' + "%.2f" % Fuls + ' kN')
    print('--->     Pu (ult. capacity single conc. internal load) = ' + "%.2f" % Pu + ' kN')
    print('--->     PPmax (punching shear capacity) = ' + "%.2f" % Ppmax + ' kN')
    print('--->     Pp (shear capacity critical perimeter) = ' + "%.2f" % Pp + ' kN')
    print('--->     Mpfab (Moment capacity steel fabric reinforced) = ' + "%.2f" % Mpfab + ' kNm/m')
    print('--->     Mun (Moment capacity un-reinforced) = ' + "%.2f" % Mun + ' kNm/m')
    if (Fuls < Ppmax) and (Fuls < Pp) and (Fuls < Pu):
        print('--->     *** PASS ***')
    else:
        print('--->     *** FAIL ***')

# Example calculation
tr34(conc=C32_40, h=200, fyk=500, As=393, bar_dia=10, cover=30, Qk=157, tyre_area=74761, k30=20, N=1, r='r')  # F-15E
tr34(conc=C32_40, h=200, fyk=500, As=393, bar_dia=10, cover=30, Qk=110, tyre_area=47186, k30=20, N=1, r='r')  # Typhoon
