import csv

outF = open("processed_data.csv", "w")
with open('test.csv') as csv_file:
    csv_reader = csv.reader(csv_file, delimiter=',')
    line_count = 0
    for row in csv_reader:
        if line_count == 0:
            line_count += 1
        else:
        	lat = (((float(row[1]) - 0) * 180) / 10) - 90
        	lng = (((float(row[2]) - 0) * 360) / 10) - 180
        	outF.write(row[0] + ',,' + str(lng) + ',' + str(lat) + ',' + row[3])
        	outF.write("\n")
        	line_count += 1
    outF.close()
    print('Processed ' + str(line_count) + ' lines.')