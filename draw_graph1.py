import matplotlib.pyplot as plt
import sys

def read_data_from_file(file_path):
    data = []
    try:
        with open(file_path, 'r', encoding='utf-8') as file:
            for line in file:
                value = line.strip()
                if value.lower() == 'none':
                    break
                data.append(float(value))
        return data
    except Exception as e:
        print(f"Error: {e}")
        return None

def draw_graph(data, output_file):
    # x축 설정: 0부터 0.1 단위로 증가하는 리스트를 생성
    x = [i * 0.1 for i in range(1, len(data) + 1)]  # 1부터 시작하여 순서대로 증가하도록 수정

    plt.plot(x, data)
    plt.ylabel('Intensity of Wave')
    plt.xlabel('Time (s)')
    plt.savefig(output_file)

def calculate_statistics(data):
    if data is None:
        return None

    # 10개의 최대값 뽑기 (중복값 처리)
    max_values = sorted(set(data), reverse=True)[:10]

    max_value = max(max_values)
    min_value = min(max_values)
    avg_value = round(sum(max_values) / len(max_values), 3)

    return max_values, max_value, min_value, avg_value

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python draw_graph.py <data_file_path> <output_file_path>")
        sys.exit(1)

    data_file_path = sys.argv[1]
    output_file_path = sys.argv[2]

    # 데이터 파일에서 데이터를 읽어옴
    data = read_data_from_file(data_file_path)

    if data is not None:
        # 데이터를 가지고 그래프를 그림
        draw_graph(data, output_file_path)

        # 최대값 리스트와 최대값, 최소값, 평균값 계산
        max_values, max_value, min_value, avg_value = calculate_statistics(data)

        # 결과 파일에 최대값 리스트, 최대값, 최소값, 평균값 정보 저장
        result_file_path = output_file_path.replace(".png", ".txt")
        with open(result_file_path, 'w') as result_file:
            result_file.write("Top 10 Max Values:\n")
            for i, max_val in enumerate(max_values, start=1):
                x_val = (data.index(max_val) + 1) * 0.1  # 최대값이 발생한 x값 계산
                result_file.write(f"{x_val:.1f}s : {max_val}\n")
            result_file.write("\n")
            result_file.write(f"Overall Max Value: {max_value}\n")
            result_file.write(f"Overall Min Value: {min_value}\n")
            result_file.write(f"Overall Avg Value: {avg_value}\n")

        print("그래프가 성공적으로 그려졌고, 최대값 정보가 결과 파일에 저장되었습니다.")
    else:
        print("그래프를 그리는데 실패하였습니다.")
